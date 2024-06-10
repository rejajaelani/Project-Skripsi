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


    var elements = document.querySelectorAll("[id^='prodi-chart-']");
    
    elements.forEach(function (element) {
        var dataAttribute = element.getAttribute("data-jum-mhs-per-prodi");

        if (dataAttribute) {
            try {
                var JumMhsPerProdi = JSON.parse(dataAttribute);

                var years = JumMhsPerProdi.map((data) => data.Tahun);
                var mahasiswaAktif = JumMhsPerProdi.map((data) => data.Total_Mahasiswa_Aktif);
                var mahasiswaCuti = JumMhsPerProdi.map((data) => data.Total_Mahasiswa_Cuti);
                var mahasiswaNonAktif = JumMhsPerProdi.map((data) => data.Total_Mahasiswa_NonAktif);
                var totalMahasiswa = JumMhsPerProdi.map((data) => data.TotalMahasiswa);

                var options = {
                    chart: {
                        height: 340,
                        type: "area",
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: "smooth",
                    },
                    colors: ["#5DDAB4", "#FFC107", "#6C757D", "#9694FF"],
                    series: [
                        {
                            name: "Mahasiswa Aktif",
                            data: mahasiswaAktif,
                        },
                        {
                            name: "Mahasiswa Cuti",
                            data: mahasiswaCuti,
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

                var chart = new ApexCharts(element, options);
                chart.render();
            } catch (e) {
                console.error("Invalid JSON data:", e);
            }
        } else {
            console.error("No data found in data attribute.");
        }
    });

});
