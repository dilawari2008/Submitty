<?php
include "../header.php";

use lib\Database;

check_administrator();

print <<<HTML
<style type="text/css">
    body {
        overflow-y: scroll;
    }

    #container-students
    {
        width:700px;
        margin: 70px auto 100px;
        background-color: #fff;
        border: 1px solid #999;
        border: 1px solid rgba(0,0,0,0.3);
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;outline: 0;
        -webkit-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
        -moz-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
        box-shadow: 0 3px 7px rgba(0,0,0,0.3);
        -webkit-background-clip: padding-box;
        -moz-background-clip: padding-box;
        background-clip: padding-box;
    }

    .tooltip-inner {
        white-space:pre-wrap;
    }
</style>

<div id="container-students">
    <div class="modal-header">
        <a style="float: right" class="btn" href="{$BASE_URL}/account/admin-classlist.php?course={$course}&semester={$semester}&this[]=Students&this[]=Upload%20ClassList">Upload Class List</a>
        <h3 id="myModalLabel" style="width:20%; display:inline-block;">Students</h3>
    </div>

    <div class="modal-body" style="padding-bottom:10px; padding-top:25px;">
        <table class="table table-bordered striped-table" id="labsTable" style=" border: 1px solid #AAA;">
            <thead style="background: #E1E1E1;">
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Rotating Section</th>
                    <th>Manual Registration</th>
                </tr>
            </thead>
HTML;

Database::query("SELECT * FROM users WHERE user_group=? ORDER BY registration_section, user_id", array(4));
$last_section = -1;
foreach(Database::rows() as $student) {
    if ($student['registration_section'] != $last_section) {
        if ($last_section != -1) {
            print "            </tbody>\n";
        }
        print <<<HTML
            <tr class="info">
                <td colspan="4" style="text-align:center;" id="section-{$student['registration_section']}">
                        Students Enrolled in Registration Section {$student["registration_section"]}
                </td>
            </tr>
            <tbody>
HTML;
        $last_section = $student['registration_section'];
    }

    $firstname = getDisplayName($student);

    $manual = "";
    if ($student["manual_registration"] === true) {
        $manual = "manual";
    }
    print <<<HTML
                <tr>
                    <td>{$student['user_id']}</td>
                    <td>{$firstname} {$student['user_lastname']}</td>
                    <td>{$student['rotating_section']}</td>
                    <td>{$manual}</td>
                </tr>
HTML;
}

print <<<HTML
            </tbody>
        </table>
    </div>
</div>
HTML;

include '../footer.php';

