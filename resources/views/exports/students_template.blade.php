<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>قالب استيراد الطلاب</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .required {
            color: red;
        }
    </style>
</head>
<body>
    <h3>قالب استيراد الطلاب</h3>
    <p>يرجى ملء البيانات في الجدول أدناه واستيراد الملف إلى النظام.</p>
    <p>الحقول التي تحتوي على علامة <span class="required">*</span> هي حقول إلزامية.</p>

    <table>
        <thead>
            <tr>
                <th>full_name <span class="required">*</span></th>
                <th>class_name <span class="required">*</span></th>
                <th>student_id</th>
                <th>identity_number</th>
                <th>birth_date</th>
                <th>gender</th>
                <th>nationality</th>
                <th>phone</th>
                <th>email</th>
                <th>guardian_name</th>
                <th>guardian_relation</th>
                <th>guardian_phone</th>
                <th>guardian_email</th>
                <th>enrollment_date</th>
                <th>notes</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>أحمد محمد عبدالله</td>
                <td>1/أ</td>
                <td>STU-00001</td>
                <td>1234567890</td>
                <td>2010-01-15</td>
                <td>ذكر</td>
                <td>سعودي</td>
                <td>0501234567</td>
                <td>ahmed@example.com</td>
                <td>محمد عبدالله</td>
                <td>أب</td>
                <td>0507654321</td>
                <td>father@example.com</td>
                <td>2023-09-01</td>
                <td>ملاحظات</td>
            </tr>
            <tr>
                <td>فاطمة علي أحمد</td>
                <td>1/ب</td>
                <td>STU-00002</td>
                <td>2345678901</td>
                <td>2010-03-20</td>
                <td>أنثى</td>
                <td>سعودية</td>
                <td>0512345678</td>
                <td>fatima@example.com</td>
                <td>علي أحمد</td>
                <td>أب</td>
                <td>0518765432</td>
                <td>father@example.com</td>
                <td>2023-09-01</td>
                <td>ملاحظات</td>
            </tr>
        </tbody>
    </table>

    <h3>ملاحظات:</h3>
    <ul>
        <li>full_name: اسم الطالب الكامل (إلزامي)</li>
        <li>class_name: اسم الفصل الدراسي (إلزامي)</li>
        <li>student_id: الرقم الجامعي/المدرسي (سيتم إنشاؤه تلقائياً إذا لم يتم تقديمه)</li>
        <li>identity_number: رقم الهوية</li>
        <li>birth_date: تاريخ الميلاد بصيغة YYYY-MM-DD</li>
        <li>gender: الجنس (ذكر/أنثى)</li>
        <li>nationality: الجنسية</li>
        <li>phone: رقم هاتف الطالب</li>
        <li>email: البريد الإلكتروني للطالب</li>
        <li>guardian_name: اسم ولي الأمر</li>
        <li>guardian_relation: صلة القرابة (أب/أم/وصي)</li>
        <li>guardian_phone: رقم هاتف ولي الأمر</li>
        <li>guardian_email: البريد الإلكتروني لولي الأمر</li>
        <li>enrollment_date: تاريخ الالتحاق بالمدرسة بصيغة YYYY-MM-DD</li>
        <li>notes: ملاحظات عامة</li>
    </ul>
</body>
</html>
