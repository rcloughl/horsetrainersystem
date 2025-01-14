<?php
/*
 * Copyright 2015 by Allen Tucker. This program is part of RMHP-Homebase, which is free 
 * software.  It comes with absolutely no warranty. You can redistribute and/or 
 * modify it under the terms of the GNU General Public License as published by the 
 * Free Software Foundation (see <http://www.gnu.org/licenses/ for more information).
 */
session_start();
session_cache_expire(30);
?>
<html>
    <head>
        <title>
            CVHR Horse Training Management System
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css" />
        <style>
        	#appLink:visited {
        		color: gray; 
        	}
        </style> 
    </head>
    <body>
        <div id="container">
            <?PHP include('header.php'); ?>
            <div id="content">
                <?PHP
                include_once('domain/Horse.php');
                include_once('database/dbinfo.php');
                include_once('database/horsedb.php');
                //include_once('database/dbPersons.php');
                //include_once('domain/Person.php');
                //include_once('database/dbLog.php');
                //include_once('domain/Shift.php');
                //include_once('database/dbShifts.php');
                date_default_timezone_set('America/New_York');
                /*
            //    fix_all_birthdays();
                if ($_SESSION['_id'] != "guest") {
                    $person = retrieve_person($_SESSION['_id']);
                    echo "<p>Welcome, " . $person->get_first_name() . ", to Homebase!";
                }
                else 
                    echo "<p>Welcome!";
                echo "   Today is " . date('l F j, Y') . ".<p>";
                ?>

                <!-- your main page data goes here. This is the place to enter content -->
                <p>
                    <?PHP
                    if ($_SESSION['access_level'] == 0)
                        echo('<p> To apply for volunteering at the Portland or Bangor Ronald McDonald House, '.
                        		'please select <b>apply</b>.');
                    if ($person) {
                */
                        /*
                         * Check type of person, and display home page based on that.
                         * all: password check
                         * guests: show link to application form
                         * applicants: show status of application form
                         * Volunteers, subs: show upcoming schedule and log sheet
                         * Managers: show upcoming vacancies, birthdays, anniversaries, applicants
                         */

                        //APPLICANT CHECK
                        /*
                        if ($person->get_first_name() != 'guest' && $person->get_status() == 'applicant') {
                            //SHOW STATUS
                            echo('<div class="infobox"><p><strong>Your application has been submitted.</strong><br><br /><table><tr><td><strong>Step</strong></td><td><strong>Completed?</strong></td></tr><tr><td>Background Check</td><td>' . $person['background_check'] . '</td></tr><tr><td>Interview</td><td>' . $person['interview'] . '</td></tr><tr><td>Shadow</td><td>' . $person['shadow'] . '</td></tr></table></p></div>');
                        }

                        //VOLUNTEER CHECK
                        if ($_SESSION['access_level'] == 1) {
                        	
                        	// display upcoming schedule
                            $shifts = selectScheduled_dbShifts($person->get_id());

                            $scheduled_shifts = array();
                            foreach ($shifts as $shift) {
                                $shift_month = get_shift_month($shift);
                                $shift_day = get_shift_day($shift);
                                $shift_year = get_shift_year($shift);

                                $shift_time_s = get_shift_start($shift);
                                $shift_time_e = get_shift_end($shift);

                                $cur_month = date("m");
                                $cur_day = date("d");
                                $cur_year = date("y");

                                if ($shift_year > $cur_year)
                                    $upcoming_shifts[] = $shift;
                                else if ($shift_year == $cur_year) {
                                    if ($cur_month < $shift_month)
                                        $upcoming_shifts[] = $shift;
                                    else if ($shift_month == $cur_month) {
                                        if ($cur_day <= $shift_day) {
                                            $upcoming_shifts[] = $shift;
                                        }
                                    }
                                }
                            }
                            if ($upcoming_shifts) {
                                echo('<div class="scheduleBox"><p><strong>Your Upcoming Schedule:</strong><br /></p><ul>');
                                foreach ($upcoming_shifts as $tableId) {
                                    echo('<li type="circle">' . get_shift_name_from_id($tableId)) . '</li>';
                                }
                                echo('</ul><p>If you need to cancel an upcoming shift, please contact the <a href="mailto:allen@npfi.org">House Manager</a>.</p></div>');
                            }
                            
                            // link to personal profile for editing
                            echo('<br><div class="scheduleBox"><p><strong>Your Personal Profile:</strong><br /></p><ul>');  
                                echo('</ul><p>Go <strong><a href="personEdit.php?id='.$person->get_id()
                        	   .'">here</a></strong> to view or update your contact information.</p></div>');
                            // link to personal log sheet
                            echo('<br><div class="scheduleBox"><p><strong>Your Log Sheet:</strong><br /></p><ul>');
                                echo('</ul><p>Go <strong><a href="volunteerLog.php?id='.$person->get_id()
                        	   .'">here</a></strong> to view or enter your recent volunteering hours.</p></div>');
              
                        }
                        
                        if ($_SESSION['access_level'] == 2) {
                            //We have a manager authenticated
                            
                        	//active applicants box
                        	$con=connect();
                        	$app_query = "SELECT first_name,last_name,id,start_date FROM dbPersons WHERE status LIKE '%applicant%'  AND venue='".
                        			$_SESSION['venue']."'order by start_date desc";
                        	$applicants_tab = mysqli_query($con,$app_query);
                        	$numLines = 0;
                        	//   if (mysqli_num_rows($applicants_tab) > 0) {
                        	echo('<div class="applicantsBox"><p><strong>Open Applications / Dates:</strong><ul>');
                        	while ($thisRow = mysqli_fetch_array($applicants_tab, MYSQLI_ASSOC)) {
                        		echo('<li type="circle"><a href="' . $path . 'personEdit.php?id=' . $thisRow['id'] .'" id = "appLink">' .
                        				$thisRow['last_name'] . ', ' . $thisRow['first_name'] . '</a> / '.
                        				$thisRow['start_date'] . '</li>');
                        	}
                        	echo('</ul></p></div><br>');
                        	//    }
                        	mysqli_close($con);
                        	
                            //log box
                            echo('<div class="logBox"><p><strong>Recent Schedule Changes:</strong><br />');
                            echo('<table class="searchResults">');
                            echo('<tr><td class="searchResults"><u>Time</u></td><td class="searchResults"><u>Message</u></td></tr>');
                            $log = get_last_log_entries(5);
                            foreach ($log as $lo) {
                                echo('<tr><td class="searchResults">' . $lo[1] . '</td>' .
                                '<td class="searchResults">' . $lo[2] . '</td></tr>');
                            }
                            echo ('</table><br><a href="' . $path . 'log.php">View full log</a></p></div><br>');
                        }
                        //DEFAULT PASSWORD CHECK
                        if (md5($person->get_id()) == $person->get_password()) {
                            if (!isset($_POST['_rp_submitted']))
                                echo('<p><div class="warning"><form method="post"><p><strong>We recommend that you change your password, which is currently default.</strong><table class="warningTable"><tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr><tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr><tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr><tr><td colspan="2" align="right" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></td></tr></table></p></form></div>');
                            else {
                                //they've submitted
                                if (($_POST['_rp_newa'] != $_POST['_rp_newb']) || (!$_POST['_rp_newa']))
                                    echo('<div class="warning"><form method="post"><p>Error with new password. Ensure passwords match.</p><br /><table class="warningTable"><tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr><tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr><tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr><tr><td colspan="2" align="center" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></form></td></tr></table></div>');
                                else if (md5($_POST['_rp_old']) != $person->get_password())
                                    echo('<div class="warning"><form method="post"><p>Error with old password.</p><br /><table class="warningTable"><tr><td class="warningTable">Old Password:</td><td class="warningTable"><input type="password" name="_rp_old"></td></tr><tr><td class="warningTable">New password</td><td class="warningTable"><input type="password" name="_rp_newa"></td></tr><tr><td class="warningTable">New password<br />(confirm)</td><td class="warningTable"><input type="password" name="_rp_newb"></td></tr><tr><td colspan="2" align="center" class="warningTable"><input type="hidden" name="_rp_submitted" value="1"><input type="submit" value="Change Password"></form></td></tr></table></div>');
                                else if ((md5($_POST['_rp_old']) == $person->get_password()) && ($_POST['_rp_newa'] == $_POST['_rp_newb'])) {
                                    $newPass = md5($_POST['_rp_newa']);
                                    change_password($person->get_id(), $newPass);
                                }
                            }
                            echo('<br clear="all">');
                        }
                        
                    }
                    */
                    ?>
                    </div>
                    <?PHP //include('footer.inc'); ?>
        </div>
    </body>
</html>