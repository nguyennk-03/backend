document.addEventListener("DOMContentLoaded", function () {
    // ====== Sidebar Menu Active Link ======
    const currentUrl = window.location.href.split(/[?#]/)[0];
    const menuLinks = document.querySelectorAll("#side-menu li a");
    menuLinks.forEach((link) => {
        const href = link.href.split(/[?#]/)[0];
        if (currentUrl === href || currentUrl.startsWith(href)) {
            const li = link.closest("li");
            if (li) li.classList.add("active");
        }
    });

    // ====== Fade out success message ======
    const successMessage = document.querySelector("#success-message");
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.transition = "opacity 0.5s ease";
            successMessage.style.opacity = "0";
            setTimeout(() => {
                successMessage.remove();
            }, 500);
        }, 2000);
    }

    // ====== Initialize DataTables ======
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
                    order: [[0, "desc"]],
                });
            }
        });
    } else {
        console.error("jQuery hoặc DataTables chưa được tải.");
    }

    // ====== Tính tổng tiền đơn hàng ======
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
        let debounceTimeout;
        const debouncedCalculateTotal = () => {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(calculateTotal, 300);
        };
        productSelect.addEventListener("change", debouncedCalculateTotal);
        quantityInput.addEventListener("input", debouncedCalculateTotal);
        calculateTotal();
    }

    // ====== Reset form ======
    window.resetForm = function (button) {
        const form = button.closest("form");
        if (!form) return;
        form.reset();
        form.querySelectorAll("select, input[type='number']").forEach(
            (input) => (input.value = "")
        );
    };

    // ====== Preview ảnh ======
    function attachImagePreviewListeners() {
        document.querySelectorAll("[name='image']").forEach((input) => {
            input.removeEventListener("change", handleImagePreview);
            input.addEventListener("change", handleImagePreview);
        });
    }

    function handleImagePreview(e) {
        const input = e.target;
        const previewContainer = document.getElementById(input.dataset.preview);
        if (!previewContainer) return;
        previewContainer.innerHTML = "";
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

    attachImagePreviewListeners();
});
