<?php
namespace Drupal\wbumenudomain\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;
use Drupal\Core\File\FileSystem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Drupal\file\Entity\File;

/**
 * Returns responses for filesmanager routes.
 */
class PagesController extends ControllerBase
{

    function Pages()
    {
        $build['content'] = [
            '#markup' => '',
            '#title' => 'Nos plus belles realisations'
        ];
        return $build;
    }
}