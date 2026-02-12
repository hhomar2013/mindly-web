<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class universty_student_details extends Model
{
    protected $table = 'universty_student_details';

    protected $fillable = [
        'education_stage_id',
        'university_faculty_id',
        'university_institute_id',
        'university_academic_year_id',
    ];

    public function student()
    {
        return $this->morphOne(Students::class, 'education', 'education_type', 'education_id');
    }

    public function educationStage()
    {
        return $this->belongsTo(EducationStage::class, 'education_stage_id');
    }

    /**
     * علاقة الكلية
     */
    public function faculty()
    {
        return $this->belongsTo(UniversityFaculty::class, 'university_faculty_id');
    }

    /**
     * علاقة المعهد (في حال لم يكن في كلية)
     */
    public function institute()
    {
        return $this->belongsTo(UniversityInstitute::class, 'university_institute_id');
    }

    /**
     * علاقة السنة الدراسية الجامعية (فرقة أولى، ثانية...)
     */
    public function academicYear()
    {
        return $this->belongsTo(UniversityAcademicYear::class, 'university_academic_year_id');
    }
}
