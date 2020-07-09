<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 8/12/2018
 * Time: 6:15 PM
 */

namespace Exam\Services;

use Exam\Models\ExamUser;
use Exam\Models\Question;
use Exam\Notifications\ReviewRequestToTeacher;
use Image;
use Permit\Models\User;
use Notification;

class CertificateService
{
    /**
     * @var ExamUser
     */
    protected $examUser;
    /**
     * @var Image
     */
    protected $image;

    /**
     * @var User;
     */
    protected $user;

    public $fileName = '';

    public $filePath;

    public function __construct(ExamUser $examUser)
    {
        $this->examUser = $examUser;
        $this->user = $examUser->user;
        $this->fileName = 'images/exams/result_' . $this->examUser->id . '.png';
        $this->filePath = storage_path('app/public/' . $this->fileName);
    }

    public function make()
    {
        $this->image = Image::canvas(450, 235, '#fffff');
        $url = $this->user->getAvatarThumb();
        $photo = Image::make($url);
        $photo->resize(180, 180);
        $this->image = $this->image->insert($photo);
        $this->image = $this->image->text($this->examUser->exam->title, 190, 10,
            function ($font) {
                $font->file(public_path('permit/fonts/BLKCHCRY.TTF'));
                $font->size(14);
                $font->color('#000000');
                $font->valign('top');
            });
        $this->image = $this->image->text($this->examUser->getCorrectionRate() . '% correction rate', 280, 80,
            function ($font) {
                $font->file(public_path('permit/fonts/BLKCHCRY.TTF'));
                $font->size(24);
                $font->color('#00ff00');
                $font->align('center');
                $font->valign('top');
            });
        $this->image = $this->image->text('Date of Exam ' . date('d M Y'), 190, 60,
            function ($font) {
                $font->file(public_path('permit/fonts/BLKCHCRY.TTF'));
                $font->size(16);
                $font->color('#000000');
                $font->valign('top');
            });
        $this->image = $this->image->text($this->user->name, 50, 190,
            function ($font) {
                $font->file(public_path('permit/fonts/BLKCHCRY.TTF'));
                $font->size(15);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });
        $tag = $this->examUser->exam->tag;
        if ($tag) {
            $this->image = $this->image->text('Difficulty Level:  ' . $tag->name, 190, 120,
                function ($font) {
                    $font->file(public_path('permit/fonts/BLKCHCRY.TTF'));
                    $font->color('#000000');
                    $font->size(16);
                    $font->valign('top');
                });
        }
        $this->image = $this->image->text('All right reserveed ' . config('app.url'), 200, 220,
            function ($font) {
                $font->file(public_path('permit/fonts/BLKCHCRY.TTF'));
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });
        $this->image->save($this->filePath);
        $this->notify();
        return $this->image;
    }

    /**
     *
     */
    protected function notify()
    {
        $totalPendingQuestion = $this->examUser->exam->questions()->where('review_type', Question::REVIEW_TYPE_MANUAL)->count();
        if ($totalPendingQuestion > 0) {
            Notification::send(User::superAdmin()->get(), new ReviewRequestToTeacher($this->examUser));
        }
    }

    public function getFileName()
    {
        if (!file_exists($this->filePath)) {
            $this->make();
        }
        return 'storage/' . $this->fileName;
    }

    /**
     *
     * @return string
     */
    public function getFilePath()
    {
        if (!file_exists($this->filePath)) {
            $this->make();
        }
        return $this->filePath;
    }

}