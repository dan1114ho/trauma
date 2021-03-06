<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModuleDocument extends Model {
    protected $guarded  = array('id');

    public $timestamps = false;

    public function courseModule() {
        return $this->belongsTo('App\CourseModule');
    }

    public function getFullUrlAttribute() {
        if ($this->type === 'url') {
            return $this->url;
        }
        return 'https:'.getS3Url($this->attributes['file']);
    }

    public function getFileExtensionAttribute() {
        if ($this->type === 'file') {
            return strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
        }
        return null;
    }

    public function getIconClassAttribute() {
        if ($this->type === 'url') {
            return 'fa-globe';
        } else {
            if ($this->is_image) {
                return 'fa-picture-o';
            } else if ($this->is_video) {
                return 'fa-file-video-o';
            }
        }
        return 'fa-file-o';
    }

    public function getIsImageAttribute() {
        $extension = $this->file_extension;
        return in_array($extension, ['png', 'gif', 'jpg', 'jpeg', 'bmp']);
    }

    public function getIsDocumentAttribute() {
        $extension = $this->file_extension;
        return in_array($extension, ['doc', 'docx', 'ppt', 'pptx']);
    }

    public function getIsPdfAttribute() {
        $extension = $this->file_extension;
        return in_array($extension, ['pdf']);
    }

    public function getIsVideoAttribute() {
        $extension = $this->file_extension;
        return in_array($extension, ['mp4', 'ogv', 'webm']);
    }
}
