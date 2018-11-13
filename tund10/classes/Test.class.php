<?php
    class Test
    {
        //omadused ehk muutujad
        private $secretNumber;
        public $publicNumber;

        //eriline funktsioon ehk konstruktor on see mis käivitatakse kohe klassi kasutuselevõtmisel ehk objekti loomisel
        function __construct($sentNumber)
        {
            $this->secretNumber = 5;
            $this->publicNumber = $this->secretNumber * $sentNumber;
            $this->tellSecret();

        }

        function __destruct()
        {
            echo "Lõpetame";
        }


        private function tellSecret()
        {
            echo "salajane number on: " .$this->secretNumber ."! ";
        }

        public function tellInfo()
        {
            echo "\n Saladusi ei paljasta!";
        }
    }//class lõppeb
?>