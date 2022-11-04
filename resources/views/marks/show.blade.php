<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Progress Report</title>
    <style>
        .table tr th,
        .table tr td {
            font-size: 10px;
            padding: 2px 6px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<h4 class="text-center">{{$results->examInfo->exam_name}}</h4>

<table class="table table-bordered">
    <tr>
        <td style="width: 33.33%">Student Name: {{$results->examInfo->student_name}}</td>
        <td style="width: 33.33%">Student ID: {{$results->examInfo->student_code}}</td>
        <td style="width: 33.33%">Roll: {{$results->examInfo->roll}}</td>
    </tr>

    <tr>
        <td>Class: {{$results->examInfo->class}}</td>
        <td>Group: {{$results->examInfo->group}}</td>
        <td>Section: {{$results->examInfo->section}}</td>

    </tr>

    <tr>
        <td colspan="3">Academic Year: {{$results->examInfo->year}}</td>
    </tr>
</table>

<table class="table table-bordered">
    <tr>
        <th rowspan="2" style="width: 6%">SL No</th>
        <th rowspan="2">Subjects</th>
        <th colspan="2" class="text-center">Full Marks</th>
        <th colspan="2" class="text-center">Obtained Marks</th>
        <th rowspan="2" class="text-center" style="width: 8%">Total Of (Written+MCQ)</th>
        <th rowspan="2" class="text-center" style="width: 8%">100 Of (Written+MCQ)</th>
        <th rowspan="2" class="text-center" style="width: 5%;">Total Marks</th>
        <th rowspan="2" class="text-center" style="width: 5%;">Highest Marks</th>
        <th rowspan="2" class="text-center" style="width: 5%;">LG</th>
        <th rowspan="2" class="text-center" style="width: 5%;">GP</th>
    </tr>

    <tr>
        <th class="text-center" style="width: 6%">Written</th>
        <th class="text-center" style="width: 6%">MCQ</th>
        <th class="text-center" style="width: 6%">Written</th>
        <th class="text-center" style="width: 6%">MCQ</th>
    </tr>
    <?php
    $slNo = 1;
    $subjectCount = $totalPoint = $totalMarks = 0;
    ?>
    @if(!empty($results->banglaSubjects))
        @foreach($results->banglaSubjects as $key => $row)
            <tr>
                <td class="text-center">{{$slNo++}}</td>
                <td>{{$row->subject_name}}</td>
                <td class="text-center">{{$row->subjective}}</td>
                <td class="text-center">{{$row->objective}}</td>
                <td class="text-center">{{$row->subjective_mark}}</td>
                <td class="text-center">{{$row->objective_mark}}</td>
                <td class="text-center">{{$row->exam_marks}}</td>
                <td class="text-center">{{$row->total_marks}}</td>
                @if($key == 0)
                    <?php
                    $subjectCount++;
                    $totalMarks += $results->banglaTotalMarks;
                    $totalPoint += $results->banglaGradePoint;
                    ?>
                    <td rowspan="2" class="text-center">{{$results->banglaTotalMarks}}</td>
                    <td rowspan="2" class="text-center">{{$results->banglaHighestMark}}</td>
                    <td rowspan="2" class="text-center">{{$results->banglaLetterGrade}}</td>
                    <td rowspan="2" class="text-center">{{$results->banglaGradePoint}}</td>
                @endif
            </tr>
        @endforeach
    @endif

    @if(!empty($results->englishSubjects))
        @foreach($results->englishSubjects as $key => $row)
            <tr>
                <td class="text-center">{{$slNo++}}</td>
                <td>{{$row->subject_name}}</td>
                <td class="text-center">{{$row->subjective}}</td>
                <td class="text-center">{{$row->objective}}</td>
                <td class="text-center">{{$row->subjective_mark}}</td>
                <td class="text-center">{{$row->objective_mark}}</td>
                <td class="text-center">{{$row->exam_marks}}</td>
                <td class="text-center">{{$row->total_marks}}</td>
                @if($key == 0)
                    <?php
                    $subjectCount++;
                    $totalMarks += $results->englishTotalMarks;
                    $totalPoint += $results->englishGradePoint;
                    ?>
                    <td rowspan="2" class="text-center">{{$results->englishTotalMarks}}</td>
                    <td rowspan="2" class="text-center">{{$results->englishHighestMark}}</td>
                    <td rowspan="2" class="text-center">{{$results->englishLetterGrade}}</td>
                    <td rowspan="2" class="text-center">{{$results->englishGradePoint}}</td>
                @endif
            </tr>
        @endforeach
    @endif

    @if(!empty($results->compulsorySubjects))
        @foreach($results->compulsorySubjects as $key => $row)
            <?php
            $subjectCount++;
            $totalMarks += $row->total_marks;
            $totalPoint += $row->grade_point;
            $highestMarkInfo = DB::select("SELECT marks.student_code, MAX(marks.total_marks) AS highest_marks FROM marks JOIN exam_settings ON marks.exam_setting_id=exam_settings.id WHERE exam_settings.ac_year_id={$results->examInfo->ac_year_id} AND exam_settings.ac_class_id={$results->examInfo->ac_class_id} AND exam_settings.ac_group_id={$results->examInfo->ac_group_id} AND exam_settings.ac_section_id={$results->examInfo->ac_section_id} AND exam_settings.subject_id='$row->subject_id'");
            $highestMark     = (!empty($highestMarkInfo) ? $highestMarkInfo[0]->highest_marks : 0);
            ?>
            <tr>
                <td class="text-center">{{$slNo++}}</td>
                <td>{{$row->subject_name}}</td>
                <td class="text-center">{{$row->subjective}}</td>
                <td class="text-center">{{$row->objective}}</td>
                <td class="text-center">{{$row->subjective_mark}}</td>
                <td class="text-center">{{$row->objective_mark}}</td>
                <td class="text-center">{{$row->exam_marks}}</td>
                <td class="text-center">{{$row->total_marks}}</td>
                <td class="text-center">{{$row->total_marks}}</td>
                <td class="text-center">{{$highestMark}}</td>
                <td class="text-center">{{$row->letter_grade}}</td>
                <td class="text-center">{{$row->grade_point}}</td>
            </tr>
        @endforeach
    @endif

    @if(!empty($results->optionalSubjects))
        @foreach($results->optionalSubjects as $key => $row)
            <?php
            $totalMarks += $row->total_marks;
            if ($row->grade_point > 3) {
                $totalPoint += ($row->grade_point - 3);
            }
            $highestMarkInfo = DB::select("SELECT marks.student_code, MAX(marks.total_marks) AS highest_marks FROM marks JOIN exam_settings ON marks.exam_setting_id=exam_settings.id WHERE exam_settings.ac_year_id={$results->examInfo->ac_year_id} AND exam_settings.ac_class_id={$results->examInfo->ac_class_id} AND exam_settings.ac_group_id={$results->examInfo->ac_group_id} AND exam_settings.ac_section_id={$results->examInfo->ac_section_id} AND exam_settings.subject_id='$row->subject_id'");
            $highestMark     = (!empty($highestMarkInfo) ? $highestMarkInfo[0]->highest_marks : 0);
            ?>
            <tr>
                <td class="text-center">{{$slNo++}}</td>
                <td>{{$row->subject_name}}</td>
                <td class="text-center">{{$row->subjective}}</td>
                <td class="text-center">{{$row->objective}}</td>
                <td class="text-center">{{$row->subjective_mark}}</td>
                <td class="text-center">{{$row->objective_mark}}</td>
                <td class="text-center">{{$row->exam_marks}}</td>
                <td class="text-center">{{$row->total_marks}}</td>
                <td class="text-center">{{$row->total_marks}}</td>
                <td class="text-center">{{$highestMark}}</td>
                <td class="text-center">{{$row->letter_grade}}</td>
                <td class="text-center">{{$row->grade_point}}</td>
            </tr>
        @endforeach
    @endif

    <?php
    $grade = 'F';
    $point = '0.00';

    if ($totalPoint >= 5) {
        $grade = 'A+';
        $point = '5.00';
    } elseif ($totalPoint >= 4) {
        $grade = 'A';
        $point = $totalPoint;
    } elseif ($totalPoint >= 3.5) {
        $grade = 'A-';
        $point = $totalPoint;
    } elseif ($totalPoint >= 3) {
        $grade = 'B';
        $point = $totalPoint;
    } elseif ($totalPoint >= 2) {
        $grade = 'C';
        $point = $totalPoint;
    } elseif ($totalPoint >= 1) {
        $grade = 'D';
        $point = $totalPoint;
    }
    ?>

    <tr>
        <th colspan="8" class="text-right">Total</th>
        <th class="text-center">{{$totalMarks}}</th>
        <th class="text-center"></th>
        <th class="text-center">{{$grade}}</th>
        <th class="text-center">{{number_format($point, 2)}}</th>
    </tr>
</table>

</body>
</html>
