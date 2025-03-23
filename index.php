<?php
require_once 'CollatzChild_class.php';

$start = CollatzHistogram::DEFAULT_START;
$end = CollatzHistogram::DEFAULT_END;
$error = '';
$histogram = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startInput = $_POST['start'] ?? '';
    $endInput = $_POST['end'] ?? '';
    if (!is_numeric($startInput) || !is_numeric($endInput)) {
        $error = "Please enter valid numeric values.";
    } else {
        $start = (int)$startInput;
        $end = (int)$endInput;
        if ($start > $end) {
            $error = "Start value must be less than or equal to end value.";
        } else {
            try {
                $collatzHist = new CollatzHistogram();
                $histogram = $collatzHist->calculateHistogram($start, $end);
            } catch (InvalidArgumentException $e) {
                $error = $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Collatz 3x+1 Histogram Calculator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f2f2f2; }
        .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 800px; margin: auto; box-shadow: 0 0 10px #ccc; }
        form { margin-bottom: 20px; }
        label { display: inline-block; width: 100px; margin-bottom: 10px; }
        input[type="number"] { padding: 8px; width: 150px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { padding: 10px 20px; background: #007BFF; border: none; border-radius: 4px; color: #fff; cursor: pointer; }
        .error { color: red; margin-bottom: 15px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: center; }
        th { background: #007BFF; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        .bar-chart { margin-top: 20px; }
        .bar { display: flex; align-items: center; margin-bottom: 5px; }
        .bar-label { width: 100px; text-align: right; padding-right: 10px; }
        .bar-inner { height: 20px; background: #007BFF; margin-right: 10px; }
        .bar-value { width: 50px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Collatz 3x+1 Histogram Calculator</h1>
    <form method="post" action="">
        <label for="start">Start:</label>
        <input type="number" name="start" id="start" value="<?php echo htmlspecialchars($start); ?>" required><br>
        <label for="end">End:</label>
        <input type="number" name="end" id="end" value="<?php echo htmlspecialchars($end); ?>" required><br>
        <input type="submit" value="Calculate">
    </form>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($histogram !== null): ?>
        <h2>Histogram for Interval [<?php echo htmlspecialchars($start); ?>, <?php echo htmlspecialchars($end); ?>]</h2>
        <table>
            <tr>
                <th>Iteration Count</th>
                <th>Frequency</th>
            </tr>
            <?php foreach ($histogram as $iterations => $frequency): ?>
                <tr>
                    <td><?php echo htmlspecialchars($iterations); ?></td>
                    <td><?php echo htmlspecialchars($frequency); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php $maxFrequency = max($histogram); ?>
        <h2>Bar Chart Visualization</h2>
        <div class="bar-chart">
            <?php foreach ($histogram as $iterations => $frequency):
                $barWidth = ($frequency / $maxFrequency) * 300;
            ?>
                <div class="bar">
                    <span class="bar-label"><?php echo htmlspecialchars($iterations); ?></span>
                    <div class="bar-inner" style="width: <?php echo $barWidth; ?>px;"></div>
                    <span class="bar-value"><?php echo htmlspecialchars($frequency); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
