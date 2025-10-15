// Fetch data from PHP script
fetch('../graf/graf-bar.php')
  .then(response => response.json())
  .then(data => {
    // Extract cawangan, jenis aset, and chart data
    const cawangan = data.cawangan;
    const jenis = data.jenis;
    const chart_data = data.chart_data;
    const total_data = data.total_data;

    // Debugging: Log the fetched data to inspect
    console.log("Cawangan:", cawangan);
    console.log("Jenis Aset:", jenis);
    console.log("Chart Data:", chart_data);
    console.log("Total Data:", total_data);

    // Predefined colors for each jenis aset and total count
    const colors = [
      '#a8e8f9',
      '#00537a',
      '#013c58',
      '#f5a201',
      '#ffba42',
      '#ffd35b',
      '#fd5901',
      '#f78104',
      '#faab36',
      '#249ea0',
      '#008083',
      '#005f60',
    ];

    // Map of cawangan to shortform labels
    const cawanganLabels = {
      "CAWANGAN PENTADBIRAN DAN KEWANGAN": "TW",
      "CAWANGAN DASAR": "CD",
      "CAWANGAN NAIK PANGKAT": "CNP",
      "CAWANGAN PERKHIDMATAN": "CK",
      "CAWANGAN LATIHAN DAN KOMPETENSI": "CLK",
      "CAWANGAN PEMBANGUNAN ORGANISASI": "CPO",
      "UNIT PENYELARASAN DAN PENGURUSAN MAKLUMAT": "UPPM"
    };

    // Convert full cawangan names to shortform
    const shortCawangan = cawangan.map(name => cawanganLabels[name] || name);

    // Dataset for total count
    const totalDataset = {
      label: 'SEMUA ASET',
      backgroundColor: colors[0],  // First color for total count
      borderColor: colors[0],
      data: total_data  // Total data for each cawangan
    };

    // Define datasets for each jenis aset, using predefined colors
    const jenisDatasets = jenis.map((aset, index) => ({
      label: aset,  // Set label as jenis aset
      backgroundColor: colors[(index + 1) % colors.length],  // Use predefined color, skip first for total
      borderColor: colors[(index + 1) % colors.length],
      data: chart_data[aset]  // The data for each jenis aset in each cawangan
    }));

    // Combine total dataset with jenis datasets
    const datasets = [totalDataset, ...jenisDatasets];

    // Debugging: Log the datasets to check if each jenis is being included
    console.log("Datasets:", datasets);

    // Bar Chart Example (Clustered Bar Chart)
    var ctx = document.getElementById("myBarChart");
    var myBarChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: shortCawangan,  // Shortform cawangan data
        datasets: datasets  // Datasets for total and each jenis aset
      },
      options: {
        maintainAspectRatio: false,
        layout: {
          padding: {
            left: 10,
            right: 25,
            top: 25,
            bottom: 0
          }
        },
        scales: {
          xAxes: [{
            stacked: false,  // Disable stacking to create clusters
            gridLines: {
              display: false,
              drawBorder: false
            },
            ticks: {
              maxTicksLimit: shortCawangan.length  // Adjust based on number of cawangan
            },
            maxBarThickness: 25,
          }],
          yAxes: [{
            stacked: false,  // Disable stacking to create clusters
            ticks: {
              beginAtZero: true,
              padding: 10,
              callback: function(value) {
                return number_format(value);  // Format the value
              }
            },
            gridLines: {
              color: "rgb(234, 236, 244)",
              zeroLineColor: "rgb(234, 236, 244)",
              drawBorder: false,
              borderDash: [2],
              zeroLineBorderDash: [2]
            }
          }]
        },
        legend: {
          display: true  // Display the legend for total and each jenis aset
        },
        tooltips: {
          titleMarginBottom: 10,
          titleFontColor: '#6e707e',
          titleFontSize: 14,
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: true,  // Display colors in tooltips
          caretPadding: 10,
          callbacks: {
            label: function(tooltipItem, chart) {
              var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
              return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
            }
          }
        },
      }
    });
  });
