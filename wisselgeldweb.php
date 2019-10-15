<!DOCTYPE html>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>

<body>
    <?php

    //Input van bedrag
    $uitkomstEurosEnCenten = array();
    if (isset($_POST["submit"])) {
        $input = $_POST["amount"];

        try {
            if (is_null($input)) {
                throw new Exception("Je hebt geen bedrag meegegeven dat omgewisseld dient te worden");
            }
            if ($input < 0) {
                throw new Exception("Ik kan geen negatief bedrag wisselen");
            }
            if (!is_numeric($input)) {
                throw new Exception("Je hebt geen geldig bedrag meegegeven");
            }

            //Afronden op 5 centen
            $rounded = round($input / 0.05) * 0.05;
            $afgerondBedrag = number_format($rounded, 2);
            $afgerondBedrag *= 100;
            // Geld eenheden (Brieven & Munten)
            $eenheden = [
                [500, '<img src="./img/500euro.png" alt="500 euro">'],
                [200, '<img src="./img/200euro.png" alt="200 euro">'],
                [100, '<img src="./img/100euro.png" alt="100 euro">'],
                [50, '<img src="./img/50euro.png" alt="50 euro">'],
                [20, '<img src="./img/20euro.png" alt="20 euro">'],
                [10, '<img src="./img/10euro.png" alt="10 euro">'],
                [5, '<img src="./img/5euro.png" alt="5 euro">'],
                [2, '<img src="./img/2euro.png" alt="2 euro">'],
                [1, '<img src="./img/1euro.png" alt="1 euro">'],
                [0.50, '<img src="./img/50eurocent.png" alt="50 cent">'],
                [0.20, '<img src="./img/20eurocent.png" alt="20 cent">'],
                [0.10, '<img src="./img/10eurocent.png" alt="10 cent">'],
                [0.05, '<img src="./img/5eurocent.png" alt="5 cent">'],
            ];                

            //Berekening van het restbedrag en aantal eenheden
            function bereken($afgerondBedrag, $eenheden)
            {
                $aantalVanEenheid = $afgerondBedrag / $eenheden;
                $restbedrag = $afgerondBedrag % $eenheden;
                $eindBedrag = floor($aantalVanEenheid);
                return array($eindBedrag, $restbedrag);
            }

            if (is_numeric($input)) {
                //Printen van type eenheden en de hoeveelheid daarvan
                for ($i = 0; $i < count($eenheden); $i++) {
                    $eenheden[$i][0] *= 100;
                    $uitkomst = bereken($afgerondBedrag, $eenheden[$i][0]);
                    $afgerondBedrag = $uitkomst[1];
                    if ($uitkomst[0] > 0) {
                        if ($eenheden[$i] < 100) {
                            $uitkomstEurosEnCenten[] = $uitkomst[0] . " x " . $eenheden[$i][1] . PHP_EOL;
                        } else {
                            $eenheden[$i][0] /= 100;
                            $uitkomstEurosEnCenten[] = $uitkomst[0] . " x " . $eenheden[$i][1] . PHP_EOL;
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            echo "Error: " . $ex->getMessage();
        }
    }

    ?>

    <h1>Wisselgeld Calculator</h1>
    <form method="post" class="form">
        <div class="lable-and-input">
            <label for="amount">Voer een bedrag in: </label>
            <input type="number" name="amount" min=".01" step=".01" required>
        </div>
        <div class="submit-button">
            <input type="submit" name="submit" value="Bereken">
        </div>
    </form>
    <p>
        <?php
        foreach($uitkomstEurosEnCenten as $uitkomstMessage) {
            echo $uitkomstMessage;
        }
        ?>
    </p>
</body>

</html>