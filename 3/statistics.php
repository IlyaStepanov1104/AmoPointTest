<?php
include_once 'config.php';
global $pdo;
session_start();

if (isset($_POST['login']) && isset($_POST['password'])){
    $sql = $pdo->query('SELECT * from admin WHERE login="'.$_POST['login'].'"');
    $admin = $sql->fetch();
    if ($_POST['password'] === $admin['password']){
        $_SESSION['admin'] = true;
    }
}
if (!isset($_SESSION['admin']) || !$_SESSION['admin']){
    if (isset($_POST['login']) && isset($_POST['password'])){
        echo '<h3>ERROR: Admin not found! Try again!</h3>';
    }
    echo '<form method="post">
            <label for="login">
                login
                <input type="text" name="login" id="login">
            </label>
            <br><br>
            <label for="password">
                password
                <input type="password" name="password" id="password">
            </label>
            <br><br>
            <input type="submit" value="LOGIN">
        </form>';
    return;
}
$colors = ["aliceblue", "antiquewhite", "aqua", "aquamarine", "azure", "beige", "bisque", "black", "blanchedalmond", "blue", "blueviolet", "brown", "burlywood", "cadetblue", "chartreuse", "chocolate", "coral", "cornflowerblue", "cornsilk", "crimson", "cyan", "darkblue", "darkcyan", "darkgoldenrod"];
echo '<h1>Statistics</h1>
    <div class="hour">
    <h2>Hours statistic</h2>';

$res = [];

for ($i = 0; $i < 24; $i++) {
    $sql = $pdo->prepare('SELECT COUNT(*) as count FROM statistic WHERE HOUR(time)=?');
    $sql->execute([$i]);
    $res[$i] = $sql->fetch()['count'];
}

$m = max($res);
for ($i = 23; $i >= 0; $i--) {
    echo '<div class="line">';
    echo '<div class="label">' . $i . ':00</div>';
    echo '<div class="graph" style="background-color: '.$colors[$i].'; width: ' . ($res[$i] / $m * 100) . '%"></div>';
    echo '</div>';
}
echo '<div class="label_line"><div class="label"></div><div class="label_graph">';
for ($i = 0; $i <= 8; $i++) {
    echo '<div>' . ($i * $m / 8) . '</div>';
}
echo '
</div>
    </div>
    <style>
    .label_line{
            margin: 0 auto 10px auto;
            width: 900px;
    display: flex;
    justify-content: space-between;
    }
    .label_graph{
        width: 100%;
        height: 15px;
        display: flex;
    justify-content: space-between;
    }
        .line{
            margin: 0 auto 10px auto;
            width: 900px;
            display: flex;
            align-items: center;
        }
        .label{
            width: 40px;
            padding-right: 10px;
            background-color: #fff;
        }
        .graph{
            height: 15px;
        }
    </style>
';

echo '
    <h2>Citys statistic</h2>
    <div class="circle"></div>';
$sql = $pdo->query('Select city, COUNT(*) as count FROM statistic GROUP BY city');
echo '<style>
h1, h2{
    text-align: center;
    margin-top: 30px;
}
    .circle{
    width: 400px;
    height: 400px;
    margin: 30px auto;
    background-image: conic-gradient(';
$prev = 0;
$cur = 0;

$citys = $sql->fetchAll();
$res = array_map(function ($a) {return $a['count'];}, $citys);
$s = array_sum($res);
$count = count($res);
for ($i = 0; $i < $count - 1; $i++) {
    $cur += $res[$i]/$s*100;
    echo $colors[$i % $count].' '.$prev.'% '.$cur.'%, ';
    $prev = $cur;
}
echo $colors[$i % $count].' '.$cur.'% 100%';
echo ');
    border-radius: 50%;
        }
</style>';
for ($i = 0; $i < count($res); $i++) {
    $text = $citys[$i]['city'];
    echo '<div style="background-color:'.$colors[$i % $count].'">'.mb_convert_encoding($text, 'windows-1251', 'utf-8').'</div>';
}