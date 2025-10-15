<?php
include '../db-connect.php';

// Query to get all distinct cawangan
$cawangan_sql = "SELECT DISTINCT cawangan FROM ict-aset";
$cawangan_result = $conn->query($cawangan_sql);
$cawangan_data = [];

if ($cawangan_result->num_rows > 0) {
    while ($row = $cawangan_result->fetch_assoc()) {
        $cawangan_data[] = $row['cawangan'];
    }
}

// Query to get total number of assets per cawangan
$total_sql = "SELECT cawangan, COUNT(*) as total FROM ict-aset GROUP BY cawangan";
$total_result = $conn->query($total_sql);

// Store total counts in an associative array
$total_data = [];
if ($total_result->num_rows > 0) {
    while ($row = $total_result->fetch_assoc()) {
        $total_data[$row['cawangan']] = $row['total'];
    }
}

// Query to get cawangan, jenis aset, and total count
$jenis_sql = "SELECT cawangan, jenis, COUNT(*) as total FROM ict-aset GROUP BY cawangan, jenis";
$jenis_result = $conn->query($jenis_sql);

// Prepare data for the chart
$chart_data = [];
$jenis_list = [];

if ($jenis_result->num_rows > 0) {
    while ($row = $jenis_result->fetch_assoc()) {
        $jenis = $row['jenis'];
        $cawangan = $row['cawangan'];
        $total = $row['total'];

        // Store in chart data
        if (!isset($chart_data[$jenis])) {
            $chart_data[$jenis] = array_fill(0, count($cawangan_data), 0);
            $jenis_list[] = $jenis;
        }

        $index = array_search($cawangan, $cawangan_data);
        if ($index !== false) {
            $chart_data[$jenis][$index] = $total;
        }
    }
}

// Encode data in JSON format
echo json_encode([
    'cawangan' => $cawangan_data,
    'jenis' => $jenis_list,
    'chart_data' => $chart_data,
    'total_data' => array_map(function($cawangan) use ($total_data) {
        return $total_data[$cawangan] ?? 0;
    }, $cawangan_data)
]);

// Close connection
$conn->close();
?>
