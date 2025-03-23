<?php
// Collatz class
class Collatz {
    public function iterate(int $n): array {
        $sequence = [$n];
        while ($n > 1) {
            $n = ($n % 2 === 0) ? $n / 2 : 3 * $n + 1;
            $sequence[] = $n;
        }
        return $sequence;
    }
}
?>
