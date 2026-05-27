{{-- <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MUI Menu</title>
        @yield('css')
        <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Inter', sans-serif;
                -webkit-tap-highlight-color: transparent;
            }

            body {
                background-color: #f7f7f7;
                color: #333;
                line-height: 1.6;
                padding-bottom: 100px; /* Untuk memberi ruang jika ada fixed-bottom element */
            }
        </style>
    </head>
    <body>
        <main>
            @yield('content')
        </main>
    </body>
</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUI Menu</title>
    @yield('css')
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: #f7f7f7;
            color: #333;
            line-height: 1.6;
            padding-bottom: 100px;
            /* Untuk memberi ruang jika ada fixed-bottom element */
        }
    </style>
</head>

<body>
    <main>
        @yield('content')
    </main>

    <!-- Select2 JS (sebelum </body>) -->
    <!-- jQuery HARUS di atas -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- Lalu baru Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Baru script kamu -->
    <script>
        $(document).ready(function() {
            $('#metode_bayar').select2({
                placeholder: "Pilih Metode",
                width: '100%',
                minimumResultsForSearch: Infinity // Nonaktifkan fitur search
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#no_meja').select2({
                placeholder: "Pilih Nomor Meja",
                width: '100%',
                minimumResultsForSearch: Infinity,
                templateResult: function(data) {
                    if (!data.id) return data.text;
                    return $('<div class="meja-option">' + data.text + '</div>');
                },
                templateSelection: function(data) {
                    return data.text;
                }
            });

            // Tambahkan class khusus untuk styling grid
            $('#no_meja').on('select2:open', function() {
                $('.select2-results__options').addClass('grid-dropdown');
            });
        });
    </script>
</body>

</html>
