<?php
$search_by_date="SELECT * FROM jorma WHERE sejil_status='سجل-عام' AND date(add_date) BETWEEN '$start' AND '$end' ORDER BY add_date DESC";


?>