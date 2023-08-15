<?php

class Discountcodes extends Dbh
{

    protected function getDiscountcodeByCode($code)
    {
        $sql = "SELECT code, discount, active FROM discountcodes WHERE code = :code";
        $stmt = $this->connect()->prepare($sql);


        $stmt->bindParam(":code", $code, PDO::PARAM_STR); // Binding parameter
        if (!$stmt->execute()) {
            var_dump($stmt->errorInfo());
            exit();
        }
        $data = $stmt->fetch();
        if (!$data) {
            return [
                "status" => "notfound",
                "discount" =>  0
            ];
        }
        if ($data && $data["active"] == 1) {
            return [
                "status" => "success",
                "discount" => $data["discount"]
            ];
        }
        if ($data && $data["active"] == 0) {
            return [
                "status" => "code unactive",
                "discount" => 0
            ];
        }
        exit();
    }
}
