<?php 
class Employee{
    public $id;
    public $name;
    public $age;
    public $address;
    public $salary;
    public $tax;
    public function __construct($name,$address,$age,$salary,$tax){
        $this->name = $name;
        $this->address = $address;
        $this->age = $age;
        $this->salary = $salary;
        $this->tax = $tax;
    }
    public function calculateSalary(){
        return $this->salary -($this->salary * $this->tax / 100);
    }
}