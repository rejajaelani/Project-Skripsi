document.addEventListener("DOMContentLoaded", function () {
    var element1 = document.getElementById("MahasiswaPertahun");
    var dataAttribute1 = element1.getAttribute("data-jum-mhs-total-per-tahun");

    var element2 = document.getElementById("MahasiswaPertahun-Cuti");
    var dataAttribute2 = element2.getAttribute("data-jum-mhs-total-per-tahun");

    // Memastikan data JSON valid
    if (dataAttribute1) {
        try {
            var JumMhsTotalPerTahun = JSON.parse(dataAttribute1);

            // Memproses data dari controller ke format yang diterima oleh Chart.js
            var years = JumMhsTotalPerTahun.map((data) => data.Tahun);
            var mahasiswaAktif = JumMhsTotalPerTahun.map(
                (data) => data.Total_Mahasiswa_aktif
            );
            var mahasiswaCuti = JumMhsTotalPerTahun.map(
                (data) => data.Total_Mahasiswa_Cuti
            );
            var mahasiswaNonAktif = JumMhsTotalPerTahun.map(
                (data) => data.Total_Mahasiswa_NonAktif
            );
            var totalMahasiswa = JumMhsTotalPerTahun.map(
                (data) => data.TotalMahasiswa
            );

            var lineOptions = {
                chart: {
                    height: 350,
                    type: "line",
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: "smooth",
                },
                colors: ["#5DDAB4", "#6C757D", "#9694FF"],
                series: [
                    {
                        name: "Mahasiswa Aktif",
                        data: mahasiswaAktif,
                    },
                    {
                        name: "Mahasiswa Non Aktif",
                        data: mahasiswaNonAktif,
                    },
                    {
                        name: "Total Mahasiswa",
                        data: totalMahasiswa,
                    },
                ],
                xaxis: {
                    categories: years,
                },
            };

            var line = new ApexCharts(
                document.querySelector("#MahasiswaPertahun"),
                lineOptions
            );

            line.render();
        } catch (e) {
            console.error("Invalid JSON data:", e);
        }
    } else {
        console.error("No data found in data attribute.");
    }

    if (dataAttribute2) {
        try {
            var JumMhsTotalPerTahun = JSON.parse(dataAttribute2);

            // Memproses data dari controller ke format yang diterima oleh Chart.js
            var years = JumMhsTotalPerTahun.map((data) => data.Tahun);
            var mahasiswaCuti = JumMhsTotalPerTahun.map(
                (data) => data.Total_Mahasiswa_Cuti
            );

            var barOptions = {
                chart: {
                    height: 350,
                    type: "bar",
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: "smooth",
                },
                colors: ["#FFC107"],
                series: [
                    {
                        name: "Mahasiswa Cuti",
                        data: mahasiswaCuti,
                    },
                ],
                xaxis: {
                    categories: years,
                },
            };

            var bar = new ApexCharts(
                document.querySelector("#MahasiswaPertahun-Cuti"),
                barOptions
            );

            bar.render();
        } catch (e) {
            console.error("Invalid JSON data:", e);
        }
    } else {
        console.error("No data found in data attribute.");
    }

    var optionsEurope = {
        series: [
            {
                name: "series1",
                data: [
                    310, 800, 600, 430, 540, 340, 605, 805, 430, 540, 340, 605,
                ],
            },
        ],
        chart: {
            height: 80,
            type: "area",
            toolbar: {
                show: false,
            },
        },
        colors: ["#5350e9"],
        stroke: {
            width: 2,
        },
        grid: {
            show: false,
        },
        dataLabels: {
            enabled: false,
        },
        xaxis: {
            type: "datetime",
            categories: [
                "2018-09-19T00:00:00.000Z",
                "2018-09-19T01:30:00.000Z",
                "2018-09-19T02:30:00.000Z",
                "2018-09-19T03:30:00.000Z",
                "2018-09-19T04:30:00.000Z",
                "2018-09-19T05:30:00.000Z",
                "2018-09-19T06:30:00.000Z",
                "2018-09-19T07:30:00.000Z",
                "2018-09-19T08:30:00.000Z",
                "2018-09-19T09:30:00.000Z",
                "2018-09-19T10:30:00.000Z",
                "2018-09-19T11:30:00.000Z",
            ],
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
            },
        },
        show: false,
        yaxis: {
            labels: {
                show: false,
            },
        },
        tooltip: {
            x: {
                format: "dd/MM/yy HH:mm",
            },
        },
    };

    var chartEurope = new ApexCharts(
        document.querySelector("#chart-europe"),
        optionsEurope
    );

    chartEurope.render();
});
