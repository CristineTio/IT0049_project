<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\Images\Exceptions\ImageException;

/**
 * Validates, resizes, and stores an optional uploaded image inside public/.
 * Only the generated filename is meant to be saved in the database.
 */
class ImageUploader
{
    /**
     * @param string $field  Name of the file input
     * @param string $label  Human-readable name used in error messages
     * @param string $folder Folder inside public/ where images are stored
     * @param int    $size   Width and height of the square display-ready copy
     */
    public function __construct(
        private readonly string $field,
        private readonly string $label,
        private readonly string $folder,
        private readonly int $size,
    ) {
    }

    /**
     * Returns the uploaded file, or null when no file was chosen.
     */
    public function file(IncomingRequest $request): ?UploadedFile
    {
        $file = $request->getFile($this->field);

        return $file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE ? $file : null;
    }

    /**
     * Validation rules allowing only a JPG or PNG image of at most 2MB.
     */
    public function rules(): array
    {
        $field     = $this->field;
        $typeError = "The {$this->label} must be a JPG or PNG image.";

        return [
            $field => [
                'label' => $this->label,
                'rules' => [
                    "max_size[{$field},2048]",
                    "is_image[{$field}]",
                    "mime_in[{$field},image/jpg,image/jpeg,image/png]",
                    "ext_in[{$field},jpg,jpeg,png]",
                ],
                'errors' => [
                    'max_size' => "The {$this->label} must not be larger than 2MB.",
                    'is_image' => $typeError,
                    'mime_in'  => $typeError,
                    'ext_in'   => $typeError,
                ],
            ],
        ];
    }

    /**
     * Crops and resizes the image into a square display-ready copy, saves it
     * under a random filename, and returns that filename. Re-encoding the
     * image also strips anything that isn't image data.
     *
     * @throws ImageException When the image cannot be processed.
     */
    public function store(UploadedFile $file): string
    {
        $filename = $file->getRandomName();

        service('image')
            ->withFile($file->getTempName())
            ->reorient(true)
            ->fit($this->size, $this->size, 'center')
            ->save(FCPATH . $this->folder . $filename);

        return $filename;
    }

    public function processingError(): string
    {
        return "The {$this->label} could not be processed. Please try another image.";
    }

    public function delete(?string $filename): void
    {
        if ($filename === null || $filename === '') {
            return;
        }

        $path = FCPATH . $this->folder . basename($filename);

        if (is_file($path)) {
            unlink($path);
        }
    }
}
