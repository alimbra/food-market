<?php

namespace App\Infrastructure\FileHandling;

use App\Domain\Dto\FileDto;
use App\Domain\FileHandling\FileAdapterInterface;
use RuntimeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class FileSymfonyAdapter implements FileAdapterInterface
{
    /** In bytes (4MB). */
    protected const IMPORT_MAX_FILE_SIZE = 4194304;

    public function __construct(
        protected Request $request,
        protected FormInterface $form,
        protected SluggerInterface $slugger,
        protected string $savedFiles
    ) {
    }

    public function getFileDTO(): FileDTO
    {
        /** @var UploadedFile $csvFile */
        $csvFile = $this->form->get('file')->getData();

        if (0 === filesize($csvFile->getRealPath())) {
            throw new FileException('File upload failed');
        }

        $extension = $csvFile->guessExtension();
        if (null === $extension) {
            throw new FileException('invalid extension');
        }

        if (self::IMPORT_MAX_FILE_SIZE < $csvFile->getSize()) {
            throw new RuntimeException('ficher_trop_volumineux');
        }
        $originalFilename = pathinfo($csvFile->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $extension;
        $csvFile->move($this->savedFiles, $newFilename);

        return new FileDto($originalFilename . '.' . $extension, $newFilename, $extension);
    }
}
