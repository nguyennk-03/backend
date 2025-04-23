document.addEventListener("DOMContentLoaded", function () {
    // Handle success message dismissal with fade-out effect
    const successMessage = document.querySelector("#success-message");
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.transition = "opacity 0.5s ease";
            successMessage.style.opacity = "0";
            setTimeout(() => {
                successMessage.remove(); // Remove element after fade-out
            }, 500);
        }, 2000); // 2-second delay for better UX
    }

    // Initialize DataTables for all tables (pagination disabled)
    const tableIds = [
        "UserTable",
        "CategoryTable",
        "BrandTable",
        "DiscountTable",
        "OrderTable",
        "ReviewTable",
        "ProductTable",
        "OrderDetailTable",
        "ColorTable",
        "SizeTable",
        "CommentTable",
        "NewsTable",
    ];

    if (window.jQuery && $.fn.DataTable) {
        tableIds.forEach((tableId) => {
            const tableElement = document.getElementById(tableId);
            if (tableElement && tableElement.querySelector("tbody tr")) {
                $("#" + tableId).DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    language: {
                        processing: "Đang xử lý...",
                        lengthMenu: "Hiển thị _MENU_ mục mỗi trang",
                        zeroRecords: "Không tìm thấy dữ liệu",
                        info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                        infoEmpty: "Không có dữ liệu nào",
                        infoFiltered: "(lọc từ _MAX_ tổng số mục)",
                        search: "Tìm kiếm:",
                        paginate: {
                            first: "Đầu",
                            last: "Cuối",
                            next: "Tiếp",
                            previous: "Trước",
                        },
                    },
                    order: [[0, "desc"]], // Sắp xếp theo cột ID giảm dần
                });
            }
        });
    } else {
        console.error("jQuery hoặc DataTables chưa được tải.");
    }

    // Calculate total price for order form
    const productSelect = document.querySelector('select[name="product_id"]');
    const quantityInput = document.querySelector('input[name="quantity"]');
    const totalPriceInput = document.getElementById("total_price");

    function calculateTotal() {
        if (!productSelect || !quantityInput || !totalPriceInput) return;

        const price =
            parseFloat(productSelect.selectedOptions[0]?.dataset.price) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const total = price * quantity;

        totalPriceInput.value =
            total > 0 ? total.toLocaleString("vi-VN") + " VNĐ" : "0 VNĐ";
    }

    if (productSelect && quantityInput && totalPriceInput) {
        // Debounce calculateTotal to prevent excessive calls on rapid input
        let debounceTimeout;
        const debouncedCalculateTotal = () => {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(calculateTotal, 300);
        };

        productSelect.addEventListener("change", debouncedCalculateTotal);
        quantityInput.addEventListener("input", debouncedCalculateTotal);
        calculateTotal(); // Initial calculation on page load
    }

    // Reset form fields
    window.resetForm = function (button) {
        const form = button.closest("form");
        if (!form) return;

        form.reset();
        form.querySelectorAll("select, input[type='number']").forEach(
            (input) => {
                input.value = "";
            }
        );
    };

    // Dynamic variant management
    function attachVariantListeners() {
        // Add variant
        document
            .querySelectorAll("#add-variant, .add-variant")
            .forEach((button) => {
                button.addEventListener("click", () => {
                    const productId = button.dataset.productId || "";
                    const variantsContainer = document.getElementById(
                        productId ? `variants_${productId}` : "variants"
                    );
                    if (!variantsContainer) return;

                    const index =
                        variantsContainer.querySelectorAll(".variant").length;
                    const variantHtml = `
                    <div class="variant mb-3 border p-3 rounded" data-index="${index}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kích thước</label>
                                <input type="text" name="variants[${index}][size]" class="form-control border-0 shadow-sm" placeholder="Nhập kích thước (ví dụ: S, M, L)" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Màu sắc</label>
                                <input type="text" name="variants[${index}][color]" class="form-control border-0 shadow-sm" placeholder="Nhập màu sắc (ví dụ: Đỏ, Xanh)" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phần trăm giảm giá</label>
                                <input type="number" name="variants[${index}][discount_percent]" class="form-control border-0 shadow-sm" value="0" placeholder="Nhập phần trăm giảm" min="0" max="100" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số lượng</label>
                                <input type="number" name="variants[${index}][stock_quantity]" class="form-control border-0 shadow-sm" value="0" placeholder="Nhập số lượng" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hình ảnh</label>
                                <input type="file" name="variants[${index}][image]" class="form-control border-0 shadow-sm variant-image-input" accept="image/jpeg,image/png,image/jpg" data-preview="preview_variants_${
                        productId ? productId + "_" : ""
                    }${index}">
                                <div class="image-preview mt-2 d-flex flex-wrap gap-2" id="preview_variants_${
                                    productId ? productId + "_" : ""
                                }${index}"></div>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger btn-sm remove-variant">Xóa biến thể</button>
                            </div>
                        </div>
                    </div>
                `;
                    variantsContainer.insertAdjacentHTML(
                        "beforeend",
                        variantHtml
                    );
                    attachImagePreviewListeners();
                });
            });

        // Remove variant using event delegation
        document.addEventListener("click", (e) => {
            if (!e.target.classList.contains("remove-variant")) return;

            const variant = e.target.closest(".variant");
            const variantsContainer = variant.closest('[id^="variants"]');
            if (!variant || !variantsContainer) return;

            variant.remove();

            // Reindex remaining variants
            const remainingVariants =
                variantsContainer.querySelectorAll(".variant");
            remainingVariants.forEach((variant, newIndex) => {
                variant.dataset.index = newIndex;
                variant.querySelectorAll("input, select").forEach((input) => {
                    input.name = input.name.replace(
                        /variants\[\d+\]/,
                        `variants[${newIndex}]`
                    );
                });
                const previewDiv = variant.querySelector(".image-preview");
                if (previewDiv) {
                    previewDiv.id = `preview_variants_${
                        variantsContainer.id.split("_")[1] || ""
                    }_${newIndex}`;
                }
                const fileInput = variant.querySelector("input[type='file']");
                if (fileInput) {
                    fileInput.dataset.preview = `preview_variants_${
                        variantsContainer.id.split("_")[1] || ""
                    }_${newIndex}`;
                }
            });
        });
    }

    // Image preview for variant and product images
    function attachImagePreviewListeners() {
        document
            .querySelectorAll(".variant-image-input, [name='image']")
            .forEach((input) => {
                input.removeEventListener("change", handleImagePreview); // Prevent duplicate listeners
                input.addEventListener("change", handleImagePreview);
            });
    }

    function handleImagePreview(e) {
        const input = e.target;
        const previewContainer = document.getElementById(input.dataset.preview);
        if (!previewContainer) return;

        previewContainer.innerHTML = ""; // Clear previous previews
        const file = input.files[0];
        if (file && file.type.startsWith("image/")) {
            const img = document.createElement("img");
            img.src = URL.createObjectURL(file);
            Object.assign(img.style, {
                width: "100px",
                height: "100px",
                objectFit: "cover",
            });
            img.className = "rounded shadow-sm";
            previewContainer.appendChild(img);
        }
    }

    // Initialize variant and image preview listeners
    attachVariantListeners();
    attachImagePreviewListeners();
});
