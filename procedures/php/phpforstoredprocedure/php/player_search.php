<html>
<head>
    <meta charset="UTF-8">
    <title>Player Search Successful</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet' href='css/34b729901a37198f5d0414728.css'>
    <link rel="stylesheet" href="css/style.css">
    <link href="css/menu.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js" type="text/javascript"></script>  
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" />
</head>
<body>
    <canvas class="fireworks"></canvas>
    <section>
        <ul class="menu cf">
            <li style="background: none;"><a href="../../../../INDEX.html">Home</a></li>
            <li style="background: none;"><a href="../../../../search_player/player_search.html">Search</a></li>
            <li style="background: none;"><a href="../../../../update_player/update_player.html">Update</a></li>
            <li style="background: none;"><a href="../../../../insert_player/insert_new_player.html">Insert</a></li>
            <li style="background: none;"><a href="../../../../database/database.php">Database</a></li>
            <!-- <li><a href="../../../../report/project_report.html">Report</a></li> -->
            <li style="background: none;"><a href="../../../procedures.html">Procedures</a></li>
            <li style="background: none;"><a href="../../../../about/about.html">About</a></li>
        </ul>  

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "fifa";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $choices = $_POST['choices-single-defaul'];
        $input_name = $_POST['input_search_keyword'];

        // Function to execute a query
        function executeQuery($conn, $query) {
            if (!$conn->query($query)) {
                echo "Error: " . $conn->error . "<br>";
            }
        }

        // List of procedures with their creation queries
        $procedures = [
            "SearchAge" => "CREATE PROCEDURE SearchAge(IN page INT(11)) 
                            NOT DETERMINISTIC CONTAINS SQL 
                            SQL SECURITY DEFINER 
                            SELECT player_name, age, overall_rating, nationality 
                            FROM personal_details 
                            WHERE personal_details.age = page;",
            "SearchNationality" => "CREATE PROCEDURE SearchNationality(IN page VARCHAR(30)) 
                                    NOT DETERMINISTIC CONTAINS SQL 
                                    SQL SECURITY DEFINER 
                                    SELECT * FROM personal_details 
                                    WHERE personal_details.nationality = page;",
            "SearchOverallRating" => "CREATE PROCEDURE SearchOverallRating(IN page INT(11)) 
                                      NOT DETERMINISTIC CONTAINS SQL 
                                      SQL SECURITY DEFINER 
                                      SELECT * FROM personal_details 
                                      WHERE personal_details.overall_rating = page;",
            "SearchTeam" => "CREATE PROCEDURE SearchTeam(IN page VARCHAR(30)) 
                              NOT DETERMINISTIC CONTAINS SQL 
                              SQL SECURITY DEFINER 
                              SELECT pd.player_name, pd.overall_rating, pd.age, pd.nationality, od.club 
                              FROM personal_details pd 
                              JOIN other_details od ON pd.player_id = od.player_id 
                              WHERE od.club = page 
                              ORDER BY pd.player_id;",
            "SearchName" => "CREATE PROCEDURE SearchName(IN page VARCHAR(30)) 
                              NOT DETERMINISTIC CONTAINS SQL 
                              SQL SECURITY DEFINER 
                              SELECT * FROM personal_details 
                              WHERE player_name = page;",
            "Searchplayerid" => "CREATE PROCEDURE Searchplayerid(IN page INT(11)) 
                                NOT DETERMINISTIC CONTAINS SQL 
                                SQL SECURITY DEFINER 
                                SELECT * FROM personal_details 
                                WHERE player_id = page;",
            "SearchPosition" => "CREATE PROCEDURE SearchPosition(IN page VARCHAR(11)) 
                                  NOT DETERMINISTIC CONTAINS SQL 
                                  SQL SECURITY DEFINER 
                                  SELECT pd.player_name, pd.overall_rating, od.preferred_position, p.gk, p.df, p.cm, p.fr 
                                  FROM personal_details pd 
                                  JOIN other_details od ON pd.player_id = od.player_id 
                                  JOIN position p ON p.player_id = pd.player_id 
                                  WHERE od.preferred_position = page 
                                  GROUP BY pd.player_id;"
        ];

        // Drop and recreate procedures
        foreach ($procedures as $name => $query) {
            // Drop procedure if it exists
            $dropProcedure = "DROP PROCEDURE IF EXISTS `$name`;";
            executeQuery($conn, $dropProcedure);

            // Create new procedure
            executeQuery($conn, $query);
        }

        // Now proceed with your search logic
        $resultFound = false;

        if ($choices == 'AGE' && ctype_digit(strval($input_name))) {
            $call = "CALL SearchAge('$input_name')";
        } else if ($choices == 'NATIONALITY' && !ctype_digit(strval($input_name))) {
            $call = "CALL SearchNationality('$input_name')";
        } else if ($choices == 'OVERALL RATING' && ctype_digit(strval($input_name))) {
            $call = "CALL SearchOverallRating('$input_name')";
        } else if ($choices == 'PLAYER ID' && ctype_digit(strval($input_name))) {
            $call = "CALL Searchplayerid('$input_name')";
        } else if ($choices == 'TEAM' && !ctype_digit(strval($input_name))) {
            $call = "CALL SearchTeam('$input_name')";
        } else if ($choices == 'PLAYING POSITION' && !ctype_digit(strval($input_name))) {
            $call = "CALL SearchPosition('$input_name')";
        } else if ($choices == 'PLAYER NAME' && !ctype_digit(strval($input_name))){
            $call = "CALL SearchName('$input_name')";
        } else {
            header("Location: index.html");
            exit;
        }

        $result = mysqli_query($conn, $call);

        if ($result && $result->num_rows > 0) {
            $resultFound = true;
            ?>
            <div class="tbl-header">
            <table cellpadding="0" cellspacing="0" border="0">
                <thead>
                    <tr>
                        <?php
                        // Output table headers based on the search type
                        if ($choices == 'PLAYER ID') {
                            echo "<th>NAME</th><th>AGE</th><th>OVERALL RATING</th><th>NATIONALITY</th><th>PLAYER ID</th><th>POSITION</th><th>GOALKEEPER</th><th>DEFENDER</th><th>CENTER-MID</th><th>FORWARD</th>";
                        } else if ($choices == 'PLAYING POSITION') {
                            echo "<th>NAME</th><th>AGE</th><th>OVERALL RATING</th><th>POSITION</th><th>GOALKEEPER</th><th>DEFENDER</th><th>CENTER-MID</th><th>FORWARD</th>";
                        } else if ($choices == 'AGE') {
                            echo "<th>NAME</th><th>AGE</th><th>OVERALL RATING</th><th>NATIONALITY</th>";
                        } else if ($choices == 'NATIONALITY') {
                            echo "<th>NAME</th><th>AGE</th><th>OVERALL RATING</th><th>NATIONALITY</th>";
                        } else if ($choices == 'OVERALL RATING') {
                            echo "<th>NAME</th><th>AGE</th><th>OVERALL RATING</th><th>NATIONALITY</th>";
                        } else if ($choices == 'TEAM') {
                            echo "<th>NAME</th><th>AGE</th><th>OVERALL RATING</th><th>NATIONALITY</th><th>TEAM</th>";
                        } else if ($choices == 'PLAYER NAME') {
                            echo "<th>NAME</th><th>AGE</th><th>OVERALL RATING</th><th>NATIONALITY</th>";
                        }
                        ?>
                    </tr>
                </thead>    
            </table> 
        </div>
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0">
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) { 
                        echo "<tr>";
                        if ($choices == 'PLAYER ID') {
                            echo("<td>" . $row["player_name"] . "</td><td>" . $row["age"] . "</td><td>" . $row["overall_rating"] . "</td><td>" . $row["nationality"] . "</td><td>" . $row["player_id"] . "</td>");
                            echo("<td>" . (isset($row["preferred_position"]) ? $row["preferred_position"] : 'N/A') . "</td>");
                            echo("<td>" . (isset($row["gk"]) ? $row["gk"] : 'N/A') . "</td>");
                            echo("<td>" . (isset($row["df"]) ? $row["df"] : 'N/A') . "</td>");
                            echo("<td>" . (isset($row["cm"]) ? $row["cm"] : 'N/A') . "</td>");
                            echo("<td>" . (isset($row["fr"]) ? $row["fr"] : 'N/A') . "</td>");
                        } else if ($choices == 'PLAYING POSITION') {
                            echo("<td>" . $row["player_name"] . "</td><td>" . $row["age"] . "</td><td>" . $row["overall_rating"] . "</td>");
                            echo("<td>" . (isset($row["preferred_position"]) ? $row["preferred_position"] : 'N/A') . "</td>");
                            echo("<td>" . (isset($row["gk"]) ? $row["gk"] : 'N/A') . "</td>");
                            echo("<td>" . (isset($row["df"]) ? $row["df"] : 'N/A') . "</td>");
                            echo("<td>" . (isset($row["cm"]) ? $row["cm"] : 'N/A') . "</td>");
                            echo("<td>" . (isset($row["fr"]) ? $row["fr"] : 'N/A') . "</td>");
                        } else if ($choices == 'AGE' || $choices == 'NATIONALITY' || $choices == 'OVERALL RATING' || $choices == 'PLAYER NAME') {
                            echo("<td>" . $row["player_name"] . "</td><td>" . $row["age"] . "</td><td>" . $row["overall_rating"] . "</td><td>" . $row["nationality"] . "</td>");
                        } else if ($choices == 'TEAM') {
                            echo("<td>" . $row["player_name"] . "</td><td>" . $row["age"] . "</td><td>" . $row["overall_rating"] . "</td><td>" . $row["nationality"] . "</td><td>" . $row["club"] . "</td>");
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>


            <?php
        } else {
            header("Location: index.html");
            exit;
        }

        $conn->close();
        ?>
    </section>
</body>
</html>
