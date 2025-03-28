document.addEventListener("DOMContentLoaded", function () {
    const successMessage = document.querySelector("#success-message");
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.transition = "opacity 0.5s ease";
            successMessage.style.opacity = "0";
            setTimeout(() => {
                successMessage.style.display = "none";
                successMessage.style.opacity = "1"; 
            }, 500);
        }, 2000);
    }

    // Danh sách các bảng cần khởi tạo DataTable
    const tableIds = [
        "UserTable",
        "CategoryTable",
        "BrandTable",
        "DiscountTable",
        "OrderTable",
        "ReviewTable",
        "ProductTable",
    ];

    if (window.jQuery && $.fn.DataTable) {
        tableIds.forEach((tableId) => {
            const tableElement = document.getElementById(tableId);
            if (tableElement) {
                const rows = tableElement.querySelectorAll("tbody tr");
                if (rows.length === 0) {
                    console.warn(
                        `Bảng ${tableId} không có dữ liệu để hiển thị.`
                    );
                    return;
                }

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
            } else {
                console.error(`Không tìm thấy bảng với ID: ${tableId}`);
            }
        });
    } else {
        console.error("jQuery hoặc DataTables chưa được tải.");
    }
});
