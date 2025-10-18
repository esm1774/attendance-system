<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Excuse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * عرض صفحة تسجيل الحضور والغيابة
     */
    public function index()
    {
        $classes = SchoolClass::with(['grade', 'students'])->active()->get();
        $subjects = Subject::active()->get();

        return view('attendances.index', compact('classes', 'subjects'));
    }

    /**
     * عرض نموذج تسجيل الحضور والغيابة للفصل
     */
    public function showClassAttendanceForm(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date'
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $subject = Subject::findOrFail($request->subject_id);
        $date = $request->date;

        // جلب الطلاب النشطين في الفصل
        $students = $class->students()->active()->get();

        // جلب سجلات الحضور لهذا اليوم والمادة
        $attendances = Attendance::where('class_id', $class->id)
            ->where('subject_id', $subject->id)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('student_id');

        return view('attendances.class_attendance', compact('class', 'subject', 'date', 'students', 'attendances'));
    }

    /**
     * عرض سجلات الحضور والغيابة لفصل معين
     */
    public function showClassAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date'
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $subject = Subject::findOrFail($request->subject_id);
        $date = $request->date;

        // جلب الطلاب النشطين في الفصل
        $students = $class->students()->active()->get();

        // جلب سجلات الحضور لهذا اليوم والمادة
        $attendances = Attendance::where('class_id', $class->id)
            ->where('subject_id', $subject->id)
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('student_id');

        return view('attendances.show_class', compact('class', 'subject', 'date', 'students', 'attendances'));
    }

    /**
     * حفظ سجلات الحضور والغيابة
     */
public function store(Request $request)
{
    $request->validate([
        'class_id' => 'required|exists:school_classes,id',
        'subject_id' => 'required|exists:subjects,id',
        'date' => 'required|date',
        'attendances' => 'required|array',
    ]);

    // أثناء التطوير، استخدم معرف مستخدم افتراضي
    $userId = 3; // <-- ضع هنا أي ID موجود في جدول users

    $classId = $request->class_id;
    $subjectId = $request->subject_id;
    $date = $request->date;

    try {
        foreach ($request->attendances as $studentId => $attendanceData) {
            if (!isset($attendanceData['student_id'])) continue;

            Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'subject_id' => $subjectId,
                    'attendance_date' => $date,
                ],
                [
                    'class_id' => $classId,
                    'status' => $attendanceData['status'] ?? 'absent',
                    'arrival_time' => $attendanceData['arrival_time'] ?? null,
                    'departure_time' => $attendanceData['departure_time'] ?? null,
                    'notes' => $attendanceData['notes'] ?? null,
                    'recorded_by' => $userId,
                ]
            );
        }

        return redirect()->back()->with('success', 'تم حفظ سجلات الحضور بنجاح.');
    } catch (\Exception $e) {
    // عرض الخطأ الحقيقي أثناء التطوير
    return redirect()->back()->with('error', 'حدث خطأ: '.$e->getMessage());
}
}




    /**
     * عرض تقارير الحضور والغيابة
     */
    public function reports()
    {
        $classes = SchoolClass::with('grade')->active()->get();
        $subjects = Subject::active()->get();

        return view('attendances.reports', compact('classes', 'subjects'));
    }

    /**
     * عرض تقرير الحضور والغيابة
     */
    public function showReport(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'report_type' => 'required|in:summary,detailed'
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $subjectId = $request->subject_id;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $reportType = $request->report_type;

        // جلب سجلات الحضور
        $query = Attendance::where('class_id', $class->id)
            ->whereBetween('attendance_date', [$fromDate, $toDate]);

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $attendances = $query->with(['student', 'subject', 'recordedBy'])->get();

        if ($reportType == 'summary') {
            // تقرير ملخص
            $students = $class->students()->active()->get();
            $reportData = [];

            foreach ($students as $student) {
                $studentAttendances = $attendances->where('student_id', $student->id);

                $reportData[$student->id] = [
                    'student' => $student,
                    'total_days' => $studentAttendances->count(),
                    'present_days' => $studentAttendances->where('status', 'present')->count(),
                    'absent_days' => $studentAttendances->where('status', 'absent')->count(),
                    'late_days' => $studentAttendances->where('status', 'late')->count(),
                    'excused_days' => $studentAttendances->where('status', 'excused')->count(),
                    'attendance_rate' => $studentAttendances->count() > 0 
                        ? round(($studentAttendances->whereIn('status', ['present', 'late', 'excused'])->count() / $studentAttendances->count()) * 100, 2)
                        : 0
                ];
            }

            return view('attendances.summary_report', compact('class', 'subjectId', 'fromDate', 'toDate', 'reportData'));
        } else {
            // تقرير مفصل
            return view('attendances.detailed_report', compact('class', 'subjectId', 'fromDate', 'toDate', 'attendances'));
        }
    }

    /**
     * عرض صفحة إدارة الأعذار
     */
    public function excuses()
    {
        $excuses = Excuse::with(['student', 'attendance', 'approvedBy'])
            ->latest()
            ->paginate(20);

        return view('attendances.excuses', compact('excuses'));
    }

    /**
     * الموافقة على عذر أو رفضه
     */
    public function updateExcuseStatus(Request $request, Excuse $excuse)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|max:255'
        ]);

        $excuse->update([
            'status' => $request->status,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->status == 'rejected' ? $request->rejection_reason : null
        ]);

        // إذا تم قبول العذر، تحديث سجل الحضور
        if ($request->status == 'approved' && $excuse->attendance) {
            $excuse->attendance->update(['status' => 'excused']);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة العذر بنجاح');
    }

    /**
     * عرض إحصائيات الحضور والغيابة
     */
    public function statistics()
    {
        // إحصائيات الحضور والغيابة العامة
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $stats = [
            'today' => [
                'total' => Attendance::whereDate('attendance_date', $today)->count(),
                'present' => Attendance::whereDate('attendance_date', $today)->where('status', 'present')->count(),
                'absent' => Attendance::whereDate('attendance_date', $today)->where('status', 'absent')->count(),
                'late' => Attendance::whereDate('attendance_date', $today)->where('status', 'late')->count(),
                'excused' => Attendance::whereDate('attendance_date', $today)->where('status', 'excused')->count(),
            ],
            'this_month' => [
                'total' => Attendance::whereDate('attendance_date', '>=', $thisMonth)->count(),
                'present' => Attendance::whereDate('attendance_date', '>=', $thisMonth)->where('status', 'present')->count(),
                'absent' => Attendance::whereDate('attendance_date', '>=', $thisMonth)->where('status', 'absent')->count(),
                'late' => Attendance::whereDate('attendance_date', '>=', $thisMonth)->where('status', 'late')->count(),
                'excused' => Attendance::whereDate('attendance_date', '>=', $thisMonth)->where('status', 'excused')->count(),
            ],
            'this_year' => [
                'total' => Attendance::whereDate('attendance_date', '>=', $thisYear)->count(),
                'present' => Attendance::whereDate('attendance_date', '>=', $thisYear)->where('status', 'present')->count(),
                'absent' => Attendance::whereDate('attendance_date', '>=', $thisYear)->where('status', 'absent')->count(),
                'late' => Attendance::whereDate('attendance_date', '>=', $thisYear)->where('status', 'late')->count(),
                'excused' => Attendance::whereDate('attendance_date', '>=', $thisYear)->where('status', 'excused')->count(),
            ],
        ];

        // إحصائيات الأعذار
        $excuseStats = [
            'pending' => Excuse::where('status', 'pending')->count(),
            'approved' => Excuse::where('status', 'approved')->count(),
            'rejected' => Excuse::where('status', 'rejected')->count(),
        ];

        // الفصول ذات أعلى معدلات الغياب
        $absenteeismByClass = DB::table('attendances')
            ->join('school_classes', 'attendances.class_id', '=', 'school_classes.id')
            ->select(
                'school_classes.id',
                'school_classes.name',
                'school_classes.name_ar',
                DB::raw('COUNT(*) as total_attendances'),
                DB::raw('SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END) as absences'),
                DB::raw('ROUND((SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as absenteeism_rate')
            )
            ->whereDate('attendance_date', '>=', $thisMonth)
            ->groupBy('school_classes.id', 'school_classes.name', 'school_classes.name_ar')
            ->orderBy('absenteeism_rate', 'desc')
            ->limit(10)
            ->get();

        // الطلاب الأكثر غيابًا
        $mostAbsentStudents = DB::table('attendances')
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->select(
                'students.id',
                'students.full_name',
                'students.student_id',
                DB::raw('COUNT(*) as total_attendances'),
                DB::raw('SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END) as absences'),
                DB::raw('ROUND((SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as absenteeism_rate')
            )
            ->whereDate('attendance_date', '>=', $thisMonth)
            ->groupBy('students.id', 'students.full_name', 'students.student_id')
            ->orderBy('absenteeism_rate', 'desc')
            ->limit(10)
            ->get();

        return view('attendances.statistics', compact('stats', 'excuseStats', 'absenteeismByClass', 'mostAbsentStudents'));
    }
}
