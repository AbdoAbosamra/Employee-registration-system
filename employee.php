<?php 
class Employee{
    public $id;
    public $name;
    public $age;
    public $address;
    public $salary;
    public $tax;
    public $department;
    public $hire_date;
    public $email;
    public $phone;
    public$status;

    public function __construct($name,$address,$age,$salary,$tax,$department=null , $email=null ,$phone= null){
        $this->name = $name;
        $this->address = $address;
        $this->age = $age;
        $this->salary = $salary;
        $this->tax = $tax;
        $this->department = $department;
        $this->email = $email;
        $this->phone = $phone;
        $this->status = "active";
        $this->hire_date = date('Y-m-d');

    }
    public function calculateSalary(){
        $basicSalary = $this->salary -($this->salary * $this->tax / 100);
        $yearsOfService = $this->calculateYearOfService();
        $loyaltyBouns = $yearsOfService>5 ?($basicSalary*0.05) : 0;
        return $basicSalary + $loyaltyBouns;
    }

    public function calculateYearOfService(){
        $hireDate = new DateTime($this->hire_date);
        $now = new DateTime();
        $interval = $hireDate->diff($now);
        return $interval->y;
    }

    public function validateEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function validatePhone($phone){
        return preg_match('/^[0-9]{11}$/' , $phone);
    }
}