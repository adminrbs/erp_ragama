<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @font-face {
            font-family: 'YourCustomFont';
            src: url('/fonts/timesbd.ttf') format('timesbd'),
                url('/fonts/timesbd.ttf') format('timesbd');
            /* Add more src entries for different font formats if needed */
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'timesbd', sans-serif;
           
        }

        page_title {
            position: fixed;
            top: <?php echo $title_top ?>;
            height: <?php echo $title_height ?>;
            width: 100%;
            /*border: 1px solid black;*/

        }


        page_header {
            position: fixed;
            /*border: 1px solid black;*/
            height: <?php echo $header_height ?>;
            width: 100%;
            top: <?php echo $header_top ?>;
        }


        column_header {
            position: fixed;
            /*border: 1px solid black;*/
            height: <?php echo $column_header_height ?>;
            width: 100%;
            top: <?php echo $column_header_top ?>;
        }

        page_footer {
            position: fixed;
            /*border: 1px solid black;*/
            width: 100%;
            height: <?php echo $footer_height ?>;
            bottom: <?php echo $footer_bottom ?>;

        }

        .lbl {
            margin-top: 50px;
            position: fixed;
        }




        @page {
            margin-top: <?php echo $page_top  ?>;
            margin-bottom: 70px;
            counter-increment: page;


        }


        table,
        th,
        td {
            border: 0px solid black;
            border-collapse: collapse;
        }

        page_footer :after {
            content: counter(page);
        }
    </style>
</head>

<body>
    <no-repeat>
        <page_title>
            <?php echo $title_content ?>
        </page_title>
    </no-repeat>

    <page_header>
        <?php echo $header_content ?>
    </page_header>
    <page_footer>
        <?php echo $footer_content ?>
    </page_footer>
    <column_header>
        <?php echo $column_header_content ?>
    </column_header>
    <page_detail>
        <?php echo $detail_content ?>
    </page_detail>


</body>

</html>