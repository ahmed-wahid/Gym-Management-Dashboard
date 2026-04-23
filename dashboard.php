<div class="card">
    <div class="card-header">
        <h4>Gym Management Dashboard</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Branches</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) as count FROM branches");
                        $count = $result->fetch_assoc()['count'];
                        ?>
                        <h5 class="card-title"><?= $count ?> Branches</h5>
                        <a href="?action=view&table=branches" class="btn btn-light">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Members</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) as count FROM members");
                        $count = $result->fetch_assoc()['count'];
                        ?>
                        <h5 class="card-title"><?= $count ?> Members</h5>
                        <a href="?action=view&table=members" class="btn btn-light">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Trainers</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) as count FROM trainers");
                        $count = $result->fetch_assoc()['count'];
                        ?>
                        <h5 class="card-title"><?= $count ?> Trainers</h5>
                        <a href="?action=view&table=trainers" class="btn btn-light">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Total Product Sales (This Month)</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("
                            SELECT COALESCE(SUM(total_price), 0) as revenue, 
                                   COUNT(*) as sales_count, 
                                   COALESCE(SUM(quantity), 0) as total_quantity
                            FROM sales 
                            WHERE MONTH(sale_date) = MONTH(CURDATE()) 
                            AND YEAR(sale_date) = YEAR(CURDATE())
                        ");
                        $data = $result->fetch_assoc();
                        $revenue = $data['revenue'];
                        $sales_count = $data['sales_count'];
                        $total_quantity = $data['total_quantity'];
                        ?>
                        <h5 class="card-title">$<?= number_format($revenue, 2) ?></h5>
                        <p class="card-text">Sales: <?= $sales_count ?> | Items Sold: <?= $total_quantity ?></p>
                        <a href="?action=view&table=sales" class="btn btn-light">View Sales</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Low Stock Alerts</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("
                            SELECT COUNT(*) as low_stock_count
                            FROM products
                            WHERE stock_quantity < 5
                        ");
                        $low_stock_count = $result->fetch_assoc()['low_stock_count'];
                        ?>
                        <h5 class="card-title"><?= $low_stock_count ?> Products</h5>
                        <a href="#lowStockTable" class="btn btn-light">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header">Members by Branch</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("
                            SELECT COUNT(DISTINCT m.member_id) as total_members
                            FROM members m
                            JOIN branches b ON m.branch_id = b.branch_id
                        ");
                        $total_members = $result->fetch_assoc()['total_members'];
                        ?>
                        <h5 class="card-title"><?= $total_members ?> Total Members</h5>
                        <a href="#membersByBranchTable" class="btn btn-light">View Details</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Recent Members</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("SELECT member_id, first_name, last_name, start_date 
                                               FROM members 
                                               ORDER BY start_date DESC 
                                               LIMIT 5");
                        $recentMembers = [];
                        while ($row = $result->fetch_assoc()) {
                            $recentMembers[] = $row;
                        }
                        displayTable($conn, $recentMembers, ['first_name', 'last_name', 'start_date'], 'members');
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Recent Sales</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("SELECT s.sale_id, p.product_name, s.quantity, s.total_price, s.sale_date 
                                               FROM sales s
                                               JOIN products p ON s.product_id = p.product_id
                                               ORDER BY s.sale_date DESC 
                                               LIMIT 5");
                        $recentSales = [];
                        while ($row = $result->fetch_assoc()) {
                            $recentSales[] = $row;
                        }
                        displayTable($conn, $recentSales, ['product_name', 'quantity', 'total_price', 'sale_date'], 'sales');
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Memberships Expiring Soon (Next 3 Months)</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("
                            SELECT m.member_id, m.first_name, m.last_name, b.branch_name, m.end_date
                            FROM members m
                            JOIN branches b ON m.branch_id = b.branch_id
                            WHERE m.end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                            ORDER BY m.end_date ASC
                            LIMIT 5
                        ");
                        $expiringMembers = [];
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $expiringMembers[] = $row;
                            }
                        }
                        displayTable($conn, $expiringMembers, ['first_name', 'last_name', 'branch_name', 'end_date'], 'members');
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Top Selling Products</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("
                            SELECT p.product_id, p.product_name, SUM(s.quantity) as total_quantity
                            FROM sales s
                            JOIN products p ON s.product_id = p.product_id
                            GROUP BY p.product_id, p.product_name
                            ORDER BY total_quantity DESC
                            LIMIT 5
                        ");
                        $topProducts = [];
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $topProducts[] = $row;
                            }
                        }
                        displayTable($conn, $topProducts, ['product_name', 'total_quantity'], 'products');
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card" id="lowStockTable">
                    <div class="card-header">Low Stock Products</div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("
                            SELECT product_id, product_name, stock_quantity, price
                            FROM products
                            WHERE stock_quantity < 5
                            ORDER BY stock_quantity ASC
                            LIMIT 5
                        ");
                        $lowStockProducts = [];
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $lowStockProducts[] = $row;
                            }
                        }
                        displayTable($conn, $lowStockProducts, ['product_name', 'stock_quantity', 'price'], 'products');
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card" id="membersByBranchTable">
                    <div class="card-header">
                        <?php
                        $top_branch_result = $conn->query("
                            SELECT b.branch_name, COUNT(m.member_id) as member_count
                            FROM branches b
                            LEFT JOIN members m ON b.branch_id = m.branch_id
                            GROUP BY b.branch_id, b.branch_name
                            ORDER BY member_count DESC
                            LIMIT 1
                        ");
                        $top_branch = $top_branch_result->fetch_assoc();
                        $top_branch_text = $top_branch ? "Top Branch: {$top_branch['branch_name']} ({$top_branch['member_count']} Members)" : "Members per Branch";
                        ?>
                        <h5><?= $top_branch_text ?></h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $result = $conn->query("
                            SELECT b.branch_name, COUNT(m.member_id) as member_count
                            FROM branches b
                            LEFT JOIN members m ON b.branch_id = m.branch_id
                            GROUP BY b.branch_id, b.branch_name
                            ORDER BY member_count DESC
                            LIMIT 10
                        ");
                        ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Branch Name</th>
                                    <th>Total Members</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['branch_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['member_count']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>No records found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>