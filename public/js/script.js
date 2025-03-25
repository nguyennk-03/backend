document.addEventListener("DOMContentLoaded", function () {
    // Ẩn thông báo thành công sau 2 giây
    const successMessage = document.querySelector("#success-message");
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = "none";
        }, 2000);
    }

    // Khởi tạo DataTable nếu có jQuery và DataTables
    if (typeof $ !== "undefined" && $.fn.DataTable) {
        const dataTableOptions = {
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
        };

        const tableIds = [
            "UserTable",
            "ProductTable",
            "BrandTable",
            "CategoryTable",
            "OrderTable",
            "DiscountTable",
            "ReviewTable",
        ];

        tableIds.forEach((tableId) => {
            const tableElement = document.getElementById(tableId);
            if (tableElement) {
                $("#" + tableId).DataTable(dataTableOptions);
            }
        });
    } else {
        console.error("jQuery hoặc DataTables chưa được tải.");
    }

    // Xử lý preview ảnh cho tất cả input file (bao gồm addProductModal và editModal)
    document.querySelectorAll('input[type="file"]').forEach((input) => {
        const inputIdParts = input.id.split("img_");
        const previewId =
            inputIdParts.length > 1
                ? "preview_" + inputIdParts[1]
                : "preview_add";
        const previewContainer = document.getElementById(previewId);

        if (previewContainer) {
            input.addEventListener("change", function () {
                previewContainer.innerHTML = ""; // Xóa preview cũ

                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("img-thumbnail");
                        img.style.width = "100px";
                        img.style.height = "100px";
                        img.style.objectFit = "cover";
                        img.alt = "Preview";
                        previewContainer.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                }
            });
        } else {
            console.warn(
                `Không tìm thấy preview container cho input: ${input.id}`
            );
        }
    });
});
