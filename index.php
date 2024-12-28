<?php

use Symfony\Component\VarDumper\VarDumper;

 require_once 'db.php'; ?>
<?php require_once 'employee.php'; ?>
<?php
$success = 0;
$failed = 0;
$deleted = 0;
// inserting or updating tata
// echo isset($_POST['submit']);
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);



if (isset($_POST['submit'])) {

    $name = htmlspecialchars($_POST['name']); // using htmlspecialchars because Filter sanitize depreciated
    $address = htmlspecialchars($_POST['address']);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $salary = filter_input(INPUT_POST, 'salary', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tax = filter_input(INPUT_POST, 'tax', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    //$employee = new Employee( $name,$address,$age,$salary,$tax);
    // $employee->name = $name;
    // $employee->address = $address;
    // $employee->salary = $salary;
    // $employee->age = $age;
    // $employee->tax = $tax;
    // print_r($employee);
    $params = [':name' => $name,":address"=>$address , ":age"=>$age ,":salary"=>$salary ,":tax"=>$tax ,":id"=>$id ];

    if(isset( $_GET['action']) && $_GET['action']=='edit'&& isset($_GET['id']))
    {
        $sql = "UPDATE  employee SET name = :name  , address =  :address , age = :age  , salary = :salary ,tax = :tax WHERE id=:id ;";
    }else{
        $sql = "INSERT INTO employee SET name = :name  , address =  :address , age = :age  , salary = :salary ,tax = :tax  ;";
        array_pop( $params );
    }  
    $stmt = $conn->prepare($sql);
    if ($stmt->execute($params)) {
        $success = 1;
        header("Location: index.php");
    } else {
        $failed = 1;
    }
}
// Update
if (isset( $_GET['action']) && $_GET['action']=='edit'&& isset($_GET['id'])) {
    if ($id > 0){
        $sql = "SELECT * FROM employee WHERE id = :id";
        $result = $conn->prepare($sql);
        $foundUser = $result->execute([":id"=>$id]);
        if ($foundUser){
            $foundUser = $result->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Employee" ,['id','name','address','age','salary','tax']);
            $user = array_shift( $foundUser);
            // var_dump($user);
            
        }
    }
}
// Delete
if (isset( $_GET['action']) && $_GET['action']=='delete'&& isset($_GET['id'])) {
    if ($id > 0){
        $sql = "DELETE FROM employee WHERE id = :id";
        $result = $conn->prepare($sql);
        $deleted = $result->execute([":id"=>$id]);
        // var_dump($deleted);
        
    }


}
?>
<?php
// Reading from database
$sql = "SELECT * FROM employee ";
$stmt = $conn->query($sql);  // return object from PDO Statement
//PDO::FETCH_PROPS_LATE call constructor after setting properties
$allRows = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Employee" ,['name','address','age','salary','tax']); // fetch style=null by defualt
$allRows = (is_array($allRows) && !empty($allRows)) ? $allRows : false;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-HnW7qIBqu2Gv/+RZSmMfJ95c2fHtrLs3Q5GG9GolwNgfHuz+UzE+XoBYbPK3Av2Z+wSkkFNqsqgOY7Ybxnv53A==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


    <title>Employee Management System</title>
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width:40%;
            margin: 30px 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            float: left;
            
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }
        @media (min-width: 768px) {
        .container {
            width: 50%;
        }
    }

        .table-container {
            float: right;
            max-width: 50% ;
            /* Adjust the width as needed */
            /* Add margin for spacing */
            margin: 30px 25px;
            overflow-x: auto; /* Enable horizontal scrolling for small screens */



        }
    </style>
</head>

<body>

    <div class="container">
        <?php
        if ($success) {
            echo '<div class="alert alert-success" role="alert">
                  Employee ' . $name . ' is successfully inserted </div>';
        } elseif($failed) {
            echo '<div class="alert alert-danger" role="alert">
           OPPs,we failed in inserting Employee</div>';
        }elseif($deleted){
            echo '<div class="alert alert-warning" role="alert">
           Ohh,You deleted Employee</div>';
        }


        ?>



        <h2>Employee Details</h2>
        <form method="post">
            <div  action="index.php" class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= isset($user)? $user->name : ''?>"required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" min="22" max="60"  value="<?= isset($user)? $user->age : ''?>"required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?= isset($user)? $user->address : ''?>"required>
            </div>
            <div class="form-group">
                <label for="salary">Salary:</label>
                <input type="number" step="0.01" id="salary" name="salary" min="1500" max="9999"value="<?= isset($user)? $user->salary : ''?>" required>
            </div>
            <div class="form-group">
                <label for="tax">Tax(%):</label>
                <input type="number" step="0.01" min="1" max="5" id="tax" name="tax" value="<?= isset($user)? $user->tax : ''?>"required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Submit</button>
            </div>
        </form>
    </div>
    <div class="table-container">
        <h2 style="text-align: center; margin-bottom:10px; background:#ccc;">بيانات الموظفين</h2>
        <table class="table table-striped table-hover">
            <thead style="background:#4267b5">
                <tr>
                    <th style='background:#ffc58f;text-align:center;'>Name</th>
                    <th style='background:#ffc58f;text-align:center;'>Age</th>
                    <th style='background:#ffc58f;text-align:center;'>Address</th>
                    <th style='background:#ffc58f;text-align:center;'>Salary</th>
                    <th style='background:#ffc58f;text-align:center;'>Tax</th>
                    <th style='background:#ffc58f;text-align:center;'>Control</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($allRows !== false) {
                    foreach ($allRows as $employee) {
                        ?>
                        <tr>
                            <td style="text-align:center;"><?= $employee->name ?></td>
                            <td style="text-align:center;"><?= $employee->age ?></td>
                            <td style="text-align:center;"><?= $employee->address ?></td>
                            <td style="text-align:center;"><?= round($employee->calculateSalary()) ?> L.E</td>
                            <td style="text-align:center;"><?= $employee->tax ?></td>
                            <td style="text-align:center;">
                            <a onclick="showImages()" href="?action=edit&id=<?= $employee->id?>">
                                <img src="assets/edit.png" style='width:20px;'>
                            </a>
                            <a  onclick="if(confirm('Do you want delete this user?'))return true;showImages(); " href="?action=delete&id=<?= $employee->id?>">
                                <img src="assets/delete.png" style='width:25px;margin-left:12px;'>
                            </a>
                            </td>
                        <?php
                    }
                }else{
                    ?>
                    <td colspan="6">No employees Yet</tr>
                <?php    
                }
                ?>
                </tr>
            </tbody>

        </table>
    </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    // Function to show images
    function showImages() {
        // Get all image elements with class "hidden"
        var images = document.querySelectorAll('.hidden');

        // Loop through each image element
        images.forEach(function(image) {
            // Remove "hidden" class to show the image
            image.classList.remove('hidden');
        });
    }
</script>


</html>