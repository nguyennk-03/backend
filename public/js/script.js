document.addEventListener("DOMContentLoaded", function () {
    // Ẩn thông báo thành công sau 2 giây
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

    // Danh sách bảng cần khởi tạo DataTable
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

    // Tính tổng giá trị đơn hàng dựa trên sản phẩm và số lượng
    const productSelect = document.querySelector('select[name="product_id"]');
    const quantityInput = document.querySelector('input[name="quantity"]');
    const totalPriceInput = document.getElementById("total_price");

    function calculateTotal() {
        if (!productSelect || !quantityInput || !totalPriceInput) return;

        const selectedOption =
            productSelect.options[productSelect.selectedIndex];
        const price = selectedOption?.getAttribute("data-price")
            ? parseFloat(selectedOption.getAttribute("data-price"))
            : 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const total = price * quantity;

        totalPriceInput.value =
            total > 0 ? total.toLocaleString("vi-VN") + " VNĐ" : "0 VNĐ";
    }

    if (productSelect && quantityInput && totalPriceInput) {
        productSelect.addEventListener("change", calculateTotal);
        quantityInput.addEventListener("input", calculateTotal);
        calculateTotal(); // Tính toán lần đầu khi trang tải
    }
});
