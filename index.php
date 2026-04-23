<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gym_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = '';
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
$table = isset($_GET['table']) ? $_GET['table'] : '';

// Handle form submissions

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Branches CRUD
        if (isset($_POST['add_branch'])) {
            $branch_name = $_POST['branch_name'];
            $address = $_POST['address'];
            // 1 2 3 4 
            $result = $conn->query("SELECT MAX(branch_id) as max_id FROM branches");
            $row = $result->fetch_assoc();
            $next_id = $row['max_id'] + 1;
            
            $stmt = $conn->prepare("INSERT INTO branches (branch_id, branch_name, address) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $next_id, $branch_name, $address);
            $stmt->execute();
            $message = "Branch added successfully!";
        }
        elseif (isset($_POST['update_branch'])) {
            $branch_id = $_POST['branch_id'];
            $branch_name = $_POST['branch_name'];
            $address = $_POST['address'];
            
            $stmt = $conn->prepare("UPDATE branches SET branch_name = ?, address = ? WHERE branch_id = ?");
            $stmt->bind_param("ssi", $branch_name, $address, $branch_id);
            $stmt->execute();
            $message = "Branch updated successfully!";
        }
        
        // Trainers CRUD
        elseif (isset($_POST['add_trainer'])) {
            $trainer_name = $_POST['trainer_name'];
            $email_id = $_POST['email_id'];
            $join_date = $_POST['join_date'];
            $salary = $_POST['salary'];
            $branch_id = $_POST['branch_id'];
            
            $stmt = $conn->prepare("INSERT INTO trainers (trainer_name, email_id, join_date, salary, branch_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdi", $trainer_name, $email_id, $join_date, $salary, $branch_id);
            $stmt->execute();
            $message = "Trainer added successfully!";
        }
        elseif (isset($_POST['update_trainer'])) {
            $trainer_id = $_POST['trainer_id'];
            $trainer_name = $_POST['trainer_name'];
            $email_id = $_POST['email_id'];
            $join_date = $_POST['join_date'];
            $salary = $_POST['salary'];
            $branch_id = $_POST['branch_id'];
            
            $stmt = $conn->prepare("UPDATE trainers SET trainer_name = ?, email_id = ?, join_date = ?, salary = ?, branch_id = ? WHERE trainer_id = ?");
            $stmt->bind_param("sssdii", $trainer_name, $email_id, $join_date, $salary, $branch_id, $trainer_id);
            $stmt->execute();
            $message = "Trainer updated successfully!";
        }
        
        // Members CRUD
        elseif (isset($_POST['add_member'])) {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $weight = $_POST['weight'];
            $height = $_POST['height'];
            $gender = $_POST['gender'];
            $phone_no = $_POST['phone_no'];
            $email_id = $_POST['email_id'];
            $branch_id = $_POST['branch_id'];
            $trainer_id = $_POST['trainer_id'] ?: NULL;
            $membership_id = $_POST['membership_id'];
            $date_of_birth = $_POST['date_of_birth'];
            
            // First, get the duration from memberships table
            $result = $conn->query("SELECT duration_months FROM memberships WHERE id_plan = $membership_id");
            $duration = $result->fetch_assoc()['duration_months'];

            // Then use a simpler INSERT without subquery
            $stmt = $conn->prepare("INSERT INTO members (first_name, last_name, weight, height, gender, phone_no, email_id, branch_id, trainer_id, membership_id, start_date, end_date, date_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL ? MONTH), ?)");
            $stmt->bind_param("ssddsssiisis", $first_name, $last_name, $weight, $height, $gender, $phone_no, $email_id, $branch_id, $trainer_id, $membership_id, $duration, $date_of_birth);
            $result = $stmt->execute();

            if (!$result) {
                $message = "Error executing statement: " . $stmt->error;
            } else {
                $message = "Member added successfully! " . $conn->affected_rows . " record(s) added.";
            }
        }
        elseif (isset($_POST['update_member'])) {
            $member_id = $_POST['member_id'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $weight = $_POST['weight'];
            $height = $_POST['height'];
            $gender = $_POST['gender'];
            $phone_no = $_POST['phone_no'];
            $email_id = $_POST['email_id'];
            $branch_id = $_POST['branch_id'];
            $trainer_id = $_POST['trainer_id'] ?: NULL;
            $membership_id = $_POST['membership_id'];
            $date_of_birth = $_POST['date_of_birth'];
            
            $stmt = $conn->prepare("UPDATE members SET first_name = ?, last_name = ?, weight = ?, height = ?, gender = ?, phone_no = ?, email_id = ?, branch_id = ?, trainer_id = ?, membership_id = ?, date_of_birth = ? WHERE member_id = ?");
            $stmt->bind_param("ssddsssiissi", $first_name, $last_name, $weight, $height, $gender, $phone_no, $email_id, $branch_id, $trainer_id, $membership_id, $date_of_birth, $member_id);
            $stmt->execute();
            $message = "Member updated successfully!";
        }
        
        // Products CRUD
        elseif (isset($_POST['add_product'])) {
            $product_name = $_POST['product_name'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];
            $description = $_POST['description'];
            
            $stmt = $conn->prepare("INSERT INTO products (product_name, category, price, stock_quantity, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdis", $product_name, $category, $price, $stock_quantity, $description);
            $stmt->execute();
            $message = "Product added successfully!";
        }
        elseif (isset($_POST['update_product'])) {
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];
            $description = $_POST['description'];
            
            $stmt = $conn->prepare("UPDATE products SET product_name = ?, category = ?, price = ?, stock_quantity = ?, description = ? WHERE product_id = ?");
            $stmt->bind_param("ssdisi", $product_name, $category, $price, $stock_quantity, $description, $product_id);
            $stmt->execute();
            $message = "Product updated successfully!";
        }
        
        // Sales CRUD
        elseif (isset($_POST['add_sale'])) {
            $member_id = $_POST['member_id'] ?: NULL;
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            $price_per_unit = $_POST['price_per_unit'];
            
            $stmt = $conn->prepare("INSERT INTO sales (member_id, product_id, quantity, price_per_unit) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iidd", $member_id, $product_id, $quantity, $price_per_unit);
            $stmt->execute();
            
            // Update product stock
            $conn->query("UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE product_id = $product_id");
            
            $message = "Sale recorded successfully!";
        }
        // Specializations CRUD
            elseif (isset($_POST['add_specialization'])) {
                $specialization_name = $_POST['specialization_name'];
                
                $stmt = $conn->prepare("INSERT INTO specializations (specialization_name) VALUES (?)");
                $stmt->bind_param("s", $specialization_name);
                $stmt->execute();
                $message = "Specialization added successfully!";
            }
            elseif (isset($_POST['update_specialization'])) {
                $specialization_id = $_POST['specialization_id'];
                $specialization_name = $_POST['specialization_name'];
                
                $stmt = $conn->prepare("UPDATE specializations SET specialization_name = ? WHERE specialization_id = ?");
                $stmt->bind_param("si", $specialization_name, $specialization_id);
                $stmt->execute();
                $message = "Specialization updated successfully!";
            }
// Trainer Specializations CRUD
elseif (isset($_POST['add_trainer_specialization'])) {
    $trainer_id = $_POST['trainer_id'];
    $specialization_id = $_POST['specialization_id'];
    
    // Check if this assignment already exists
    $result = $conn->query("SELECT * FROM trainer_specializations WHERE trainer_id = $trainer_id AND specialization_id = $specialization_id");
    if ($result->num_rows > 0) {
        $message = "This specialization is already assigned to this trainer.";
    } else {
        $stmt = $conn->prepare("INSERT INTO trainer_specializations (trainer_id, specialization_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $trainer_id, $specialization_id);
        $stmt->execute();
        $message = "Specialization assigned successfully!";
    }
}
elseif (isset($_POST['update_trainer_specialization'])) {
    $original_trainer_id = (int)$_POST['original_trainer_id'];
    $original_specialization_id = (int)$_POST['original_specialization_id'];
    $trainer_id = (int)$_POST['trainer_id'];
    $specialization_id = (int)$_POST['specialization_id'];
    
    // Check if we're actually changing the values
    if ($original_trainer_id !== $trainer_id || $original_specialization_id !== $specialization_id) {
        // Check if the new combination already exists
        $result = $conn->query("SELECT * FROM trainer_specializations WHERE trainer_id = $trainer_id AND specialization_id = $specialization_id");
        if ($result->num_rows > 0) {
            $message = "This specialization is already assigned to this trainer.";
        } else {
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Delete the old record
                $stmt = $conn->prepare("DELETE FROM trainer_specializations WHERE trainer_id = ? AND specialization_id = ?");
                $stmt->bind_param("ii", $original_trainer_id, $original_specialization_id);
                $stmt->execute();
                
                // Insert the new record
                $stmt = $conn->prepare("INSERT INTO trainer_specializations (trainer_id, specialization_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $trainer_id, $specialization_id);
                $stmt->execute();
                
                // Commit transaction
                $conn->commit();
                $message = "Trainer specialization updated successfully!";
            } catch (Exception $e) {
                // Rollback on error
                $conn->rollback();
                $message = "Error: " . $e->getMessage();
            }
        }
    } else {
        $message = "No changes were made.";
    }
}
        // Feedback - Trainers
            elseif (isset($_POST['add_feedback_trainer'])) {
                $member_id = $_POST['member_id'];
                $trainer_id = $_POST['trainer_id'];
                $rating = $_POST['rating'];
                $comments = $_POST['comments'];
                $feedback_date = date('Y-m-d');
                
                $stmt = $conn->prepare("INSERT INTO feedback_trainers (member_id, trainer_id, rating, comments, feedback_date) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiss", $member_id, $trainer_id, $rating, $comments, $feedback_date);
                $stmt->execute();
                $message = "Trainer feedback submitted successfully!";
            }
            // Feedback - Trainers Update
                elseif (isset($_POST['update_feedback_trainer'])) {
                    $feedback_id = $_POST['feedback_id'];
                    $member_id = $_POST['member_id'];
                    $trainer_id = $_POST['trainer_id'];
                    $rating = $_POST['rating'];
                    $comments = $_POST['comments'];
                    
                    $stmt = $conn->prepare("UPDATE feedback_trainers SET member_id = ?, trainer_id = ?, rating = ?, comments = ? WHERE feedback_id = ?");
                    $stmt->bind_param("iiisi", $member_id, $trainer_id, $rating, $comments, $feedback_id);
                    $stmt->execute();
                    $message = "Trainer feedback updated successfully!";
                }
            // Feedback - Branches
            elseif (isset($_POST['add_feedback_branch'])) {
                $member_id = $_POST['member_id'];
                $branch_id = $_POST['branch_id'];
                $rating = $_POST['rating'];
                $comments = $_POST['comments'];
                $feedback_date = date('Y-m-d');
                
                $stmt = $conn->prepare("INSERT INTO feedback_branches (member_id, branch_id, rating, comments, feedback_date) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iiiss", $member_id, $branch_id, $rating, $comments, $feedback_date);
                $stmt->execute();
                $message = "Branch feedback submitted successfully!";
            }
            // Feedback - Branches Update
            elseif (isset($_POST['update_feedback_branch'])) {
                $feedback_id = $_POST['feedback_id'];
                $member_id = $_POST['member_id'];
                $branch_id = $_POST['branch_id'];
                $rating = $_POST['rating'];
                $comments = $_POST['comments'];
                
                $stmt = $conn->prepare("UPDATE feedback_branches SET member_id = ?, branch_id = ?, rating = ?, comments = ? WHERE feedback_id = ?");
                $stmt->bind_param("iiisi", $member_id, $branch_id, $rating, $comments, $feedback_id);
                $stmt->execute();
                $message = "Branch feedback updated successfully!";
            }
        // Remove duplicate add_sale function and just keep this one:
            elseif (isset($_POST['add_sale'])) {
                $member_id = !empty($_POST['member_id']) ? $_POST['member_id'] : NULL;
                $product_id = $_POST['product_id'];
                $quantity = $_POST['quantity'];
                $price_per_unit = $_POST['price_per_unit'];
                $total_price = $quantity * $price_per_unit;
                
                // Check if product has enough stock
                $result = $conn->query("SELECT stock_quantity FROM products WHERE product_id = $product_id");
                $product = $result->fetch_assoc();
                
                if ($product['stock_quantity'] < $quantity) {
                    $message = "Error: Not enough stock available. Only {$product['stock_quantity']} item(s) left.";
                } else {
                    // Begin transaction
                    $conn->begin_transaction();
                    
                    try {
                        // Insert sale record
                        $stmt = $conn->prepare("INSERT INTO sales (member_id, product_id, quantity, price_per_unit, total_price, sale_date) VALUES (?, ?, ?, ?, ?, CURDATE())");
                        $stmt->bind_param("iiddd", $member_id, $product_id, $quantity, $price_per_unit, $total_price);
                        $stmt->execute();
                        
                        // Update product stock
                        $conn->query("UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE product_id = $product_id");
                        
                        // Commit transaction
                        $conn->commit();
                        $message = "Sale recorded successfully!";
                    } catch (Exception $e) {
                        // Rollback on error
                        $conn->rollback();
                        $message = "Error: " . $e->getMessage();
                    }
                }
            }
        // Memberships CRUD
    elseif (isset($_POST['add_membership'])) {
        $plan_name = $_POST['plan_name'];
        $price = $_POST['price'];
        $duration_months = $_POST['duration_months'];
        
        $stmt = $conn->prepare("INSERT INTO memberships (plan_name, price, duration_months) VALUES (?, ?, ?)");
        if (!$stmt) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("sdi", $plan_name, $price, $duration_months);
            $result = $stmt->execute();
            
            if (!$result) {
                $message = "Error adding membership plan: " . $stmt->error;
            } else {
                $message = "Membership plan added successfully!";
            }
        }
    }
    elseif (isset($_POST['update_membership'])) {
        $id_plan = $_POST['id_plan'];
        $plan_name = $_POST['plan_name'];
        $price = $_POST['price'];
        $duration_months = $_POST['duration_months'];
        
        $stmt = $conn->prepare("UPDATE memberships SET plan_name = ?, price = ?, duration_months = ? WHERE id_plan = ?");
        if (!$stmt) {
            $message = "Error preparing statement: " . $conn->error;
        } else {
            $stmt->bind_param("sdii", $plan_name, $price, $duration_months, $id_plan);
            $result = $stmt->execute();
            
            if (!$result) {
                $message = "Error updating membership plan: " . $stmt->error;
            } else {
                $message = "Membership plan updated successfully!";
            }
        }
    }
    // Sales CRUD
elseif (isset($_POST['add_sale'])) {
    $member_id = !empty($_POST['member_id']) ? $_POST['member_id'] : NULL;
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price_per_unit = $_POST['price_per_unit'];
    $total_price = $quantity * $price_per_unit;
    
    // Check if product has enough stock
    $result = $conn->query("SELECT stock_quantity FROM products WHERE product_id = $product_id");
    $product = $result->fetch_assoc();
    
    if ($product['stock_quantity'] < $quantity) {
        $message = "Error: Not enough stock available. Only {$product['stock_quantity']} item(s) left.";
    } else {
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // Insert sale record
            $stmt = $conn->prepare("INSERT INTO sales (member_id, product_id, quantity, price_per_unit, total_price, sale_date) VALUES (?, ?, ?, ?, ?, CURDATE())");
            $stmt->bind_param("iiddd", $member_id, $product_id, $quantity, $price_per_unit, $total_price);
            $stmt->execute();
            
            // Update product stock
            $conn->query("UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE product_id = $product_id");
            
            // Commit transaction
            $conn->commit();
            $message = "Sale recorded successfully!";
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
        }
    }
}
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Handle delete actions
if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    $table = $_GET['table'];
    
    try {
        if ($table === 'trainer_specializations') {
            // Special handling for compound keys
            $ids = explode(',', $id);  // Split the comma-separated ID values
            if (count($ids) === 2) {
                $trainer_id = $ids[0];
                $specialization_id = $ids[1];
                $conn->query("DELETE FROM trainer_specializations WHERE trainer_id = $trainer_id AND specialization_id = $specialization_id");
            }
        } else {
            switch ($table) {
                case 'branches':
                    $conn->query("UPDATE trainers SET branch_id = NULL WHERE branch_id = $id");
                    $conn->query("DELETE FROM branches WHERE branch_id = $id");
                    break;
                case 'trainers':
                    $conn->query("UPDATE members SET trainer_id = NULL WHERE trainer_id = $id");
                    $conn->query("DELETE FROM trainer_specializations WHERE trainer_id = $id");
                    $conn->query("DELETE FROM feedback_trainers WHERE trainer_id = $id");
                    $conn->query("DELETE FROM trainers WHERE trainer_id = $id");
                    break;
                case 'members':
                    $conn->query("UPDATE sales SET member_id = NULL WHERE member_id = $id");
                    $conn->query("DELETE FROM feedback_trainers WHERE member_id = $id");
                    $conn->query("DELETE FROM feedback_branches WHERE member_id = $id");
                    $conn->query("DELETE FROM members WHERE member_id = $id");
                    break;
                case 'products':
                    $conn->query("DELETE FROM sales WHERE product_id = $id");
                    $conn->query("DELETE FROM products WHERE product_id = $id");
                    break;
                default:
                    $pk = getPrimaryKey($table);
                    if (is_array($pk)) {
                        // Skip - handled above
                    } else {
                        $conn->query("DELETE FROM $table WHERE $pk = $id");
                    }
            }
        }
        $message = "Record deleted successfully!";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Function to get primary key column name for a table
function getPrimaryKey($table) {
    switch ($table) {
        case 'branches': return 'branch_id';
        case 'trainers': return 'trainer_id';
        case 'members': return 'member_id';
        case 'memberships': return 'id_plan';
        case 'products': return 'product_id';
        case 'sales': return 'sale_id';
        case 'feedback_trainers': return 'feedback_id';
        case 'feedback_branches': return 'feedback_id';
        case 'specializations': return 'specialization_id';
        case 'trainer_specializations': return ['trainer_id', 'specialization_id'];
        default: return 'id';
    }
}

// Function to fetch data from a table
function fetchTableData($conn, $table) {
    $result = $conn->query("SELECT * FROM $table");
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Function to get a single record by ID
function getRecordById($conn, $table, $id) {
    if ($id === NULL) {
        return NULL; // Return NULL if the ID is NULL
    }
    
    $pk = getPrimaryKey($table);
    if (is_array($pk)) {
        $ids = explode(',', $id);
        $sql = "SELECT * FROM $table WHERE ";
        foreach ($pk as $i => $col) {
            if ($i > 0) $sql .= " AND ";
            $sql .= "$col = " . $ids[$i];
        }
    } else {
        $sql = "SELECT * FROM $table WHERE $pk = $id";
    }
    $result = $conn->query($sql);
    if (!$result) {
        return NULL; // Query failed
    }
    return $result->fetch_assoc();
}

// Function to get options for dropdowns
function getOptions($conn, $table, $value_col, $label_col, $selected = null) {
    // Special handling for members table with concatenated names
    if ($table === 'members' && strpos($label_col, 'CONCAT') !== false) {
        $sql = "SELECT $value_col, $label_col AS label FROM $table";
    } else {
        $sql = "SELECT $value_col, $label_col FROM $table";
    }
    
    $result = $conn->query($sql);
    $options = '';
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $value = $row[$value_col];
            $label = isset($row['label']) ? $row['label'] : $row[$label_col];
            $selected_attr = ($value == $selected) ? 'selected' : '';
            $options .= "<option value='$value' $selected_attr>$label</option>";
        }
    } else {
        $options .= "<option value=''>No options available</option>";
    }
    return $options;
}

// Function to display table data with action buttons
function displayTable($conn, $data, $columns, $table) {
    if (empty($data)) {
        echo "<p>No records found.</p>";
        return;
    }
    
    echo "<div class='table-responsive'><table class='table table-striped'>";
    echo "<thead><tr>";
    foreach ($columns as $col) {
        echo "<th>" . ucfirst(str_replace('_', ' ', $col)) . "</th>";
    }
    echo "<th>Actions</th></tr></thead><tbody>";
    
    foreach ($data as $row) {
        echo "<tr>";
        foreach ($columns as $col) {
            // Handle foreign key display
            if ($col == 'branch_id') {
                $branch = getRecordById($conn, 'branches', $row[$col]);
                echo "<td>" . ($branch ? htmlspecialchars($branch['branch_name']) : 'N/A') . "</td>";
            }
            elseif ($col == 'trainer_id') {
                $trainer = ($row[$col] !== NULL) ? getRecordById($conn, 'trainers', $row[$col]) : NULL;
                echo "<td>" . ($trainer ? htmlspecialchars($trainer['trainer_name']) : 'N/A') . "</td>";
            }
            elseif ($col == 'membership_id') {
                $membership = getRecordById($conn, 'memberships', $row[$col]);
                echo "<td>" . ($membership ? htmlspecialchars($membership['plan_name']) : 'N/A') . "</td>";
            }
            elseif ($col == 'product_id') {
                $product = getRecordById($conn, 'products', $row[$col]);
                echo "<td>" . ($product ? htmlspecialchars($product['product_name']) : 'N/A') . "</td>";
            }
            else {
                echo "<td>" . htmlspecialchars($row[$col] ?? '') . "</td>";
            }
        }
        
        // Generate ID for compound primary keys
        $pk = getPrimaryKey($table);
        if (is_array($pk)) {
            $id_parts = [];
            foreach ($pk as $col) {
                $id_parts[] = $row[$col];
            }
            $id = implode(',', $id_parts);
        } else {
            $id = $row[$pk];
        }
        
        echo "<td>
                <a href='?action=edit&table=$table&id=$id' class='btn btn-sm btn-warning'>Edit</a>
                <a href='?action=view&table=$table&delete=1&id=$id' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
              </td>";
        echo "</tr>";
    }
    
    echo "</tbody></table></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #343a40;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar .active {
            background-color: #007bff;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h3 class="text-center mb-4">Gym System</h3>
                <a href="?action=dashboard" class="<?= $action == 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                
                <h5 class="mt-4">Management</h5>
                <a href="?action=view&table=branches" class="<?= $table == 'branches' ? 'active' : '' ?>">Branches</a>
                <a href="?action=view&table=trainers" class="<?= $table == 'trainers' ? 'active' : '' ?>">Trainers</a>
                <a href="?action=view&table=members" class="<?= $table == 'members' ? 'active' : '' ?>">Members</a>
                <a href="?action=view&table=memberships" class="<?= $table == 'memberships' ? 'active' : '' ?>">Memberships</a>
                <a href="?action=view&table=products" class="<?= $table == 'products' ? 'active' : '' ?>">Products</a>
                <a href="?action=view&table=sales" class="<?= $table == 'sales' ? 'active' : '' ?>">Sales</a>
                <a href="?action=view&table=specializations" class="<?= $table == 'specializations' ? 'active' : '' ?>">Specializations</a>
                <a href="?action=view&table=trainer_specializations" class="<?= $table == 'trainer_specializations' ? 'active' : '' ?>">Trainer Specializations</a>
                
                <h5 class="mt-4">Feedback</h5>
                <a href="?action=view&table=feedback_trainers" class="<?= $table == 'feedback_trainers' ? 'active' : '' ?>">Trainer Feedback</a>
                <a href="?action=view&table=feedback_branches" class="<?= $table == 'feedback_branches' ? 'active' : '' ?>">Branch Feedback</a>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <?php if ($message): ?>
                    <div class="alert alert-info"><?= $message ?></div>
                <?php endif; ?>
                
                <?php
                switch ($action) {
                    case 'dashboard':
                        include 'dashboard.php';
                        break;
                    case 'view':
                        if ($table) {
                            $data = fetchTableData($conn, $table);
                            $columns = $data ? array_keys($data[0]) : [];
                            echo "<h2>" . ucfirst(str_replace('_', ' ', $table)) . "</h2>";
                            displayTable($conn, $data, $columns, $table);
                            
                            // Include appropriate form for each table
                            if (file_exists("forms/{$table}_form.php")) {
                                include "forms/{$table}_form.php";
                            }
                        }
                        break;
                    case 'edit':
                        if ($table && isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $record = getRecordById($conn, $table, $id);
                            
                            if (file_exists("forms/{$table}_form.php")) {
                                include "forms/{$table}_form.php";
                            }
                        }
                        break;
                    default:
                        include 'dashboard.php';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>