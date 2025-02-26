document.addEventListener("DOMContentLoaded", function () {
    // Ẩn thông báo thành công sau 2 giây
    let successMessage = document.querySelector("#success-message");
    if (successMessage) {
        setTimeout(() => (successMessage.style.display = "none"), 2000);
    }

    // Kiểm tra xem jQuery và DataTables có sẵn không
    if (typeof $ !== "undefined" && $.fn.DataTable) {
        const dataTableOptions = {
            paging: true,
            searching: true,
            ordering: true,
            language: {
                processing: "Đang xử lý...",
                lengthMenu: "Hiển thị _MENU_ người dùng mỗi trang",
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
            order: [[0, "desc"]], // Sắp xếp theo ID giảm dần
        };

        // Danh sách bảng cần áp dụng DataTables
        let tableIds = [
            "UserTable",
            "ProductTable",
            "BrandTable",
            "OrderTable",
            "DiscountTable",
            "ReviewTable",
        ];

        tableIds.forEach((tableId) => {
            if (document.getElementById(tableId)) {
                $("#" + tableId).DataTable(dataTableOptions);
            }
        });
    } else {
        console.error("jQuery hoặc DataTables chưa được tải.");
    }
});
