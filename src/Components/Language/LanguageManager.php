<?php

namespace App\Components\Language;

use App\Components\Utils\SchemaReader;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LanguageManager
{
    private $requestStack;

    private $schemaReader;

    private $languages;

    private $tokenStorkage;

    private $em;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $request_stack, SchemaReader $schema_reader, SessionInterface $session, TokenStorageInterface $tokenStorage)
    {
        $this->requestStack = $request_stack;
        $this->schemaReader = $schema_reader;
        $this->tokenStorkage = $tokenStorage;
        $this->session = $session;
        $this->em = $entityManager;
    }

    /**
     * @return Language
     *
     * @throws \Exception
     */
    public function currentLanguage()
    {
        $user = $this->tokenStorkage->getToken()->getUser();

        $languages = $this->getLanguages();
        //User interface detection
        if ($user instanceof User) {
            $language = $user->getData()->getLanguage();
            if (isset($languages[$language])) {
                return $languages[$language];
            }
        }
        //Session check
        if ($lang = $this->requestStack->getCurrentRequest()->get('lang')) {
            if (isset($languages[$lang])) {
                $this->session->set('_locale', $lang);

                return $languages[$lang];
            }
        }
        if ($lang = $this->session->get('_locale')) {
            if (isset($languages[$lang])) {
                return $languages[$lang];
            }
        }

        //Session ENd
        //Browser Interface detection;
        $browserLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if (isset($languages[$browserLanguage])) {
            return $languages[$browserLanguage];
        }
        //System Detected;
        $locale = $this->requestStack->getCurrentRequest()->getLocale();
        if (!isset($languages[$locale])) {
            throw new \Exception('No language in system');
        }

        return $languages[$locale];
    }

    public function getLanguages()
    {
        if (!$this->languages) {
            $this->languages = $this->schemaReader->getSchema('languages');
            $this->parseSchemaLanguages();
        }

        return $this->languages;
    }

    public function getEnabledLanguage($language_type = null)
    {
        $lists = $this->getLanguages();
        /** @var Language $language */
        foreach ($this->getLanguages() as $lang => $language) {
            if (!$language->getEnable()) {
                unset($lists[$lang]);
            }
        }
        if ($language_type) {
            return isset($lists[$language_type]) ? $lists[$language_type] : null;
        }

        return $lists;
    }

    public function getInterfaceLanguage($language_type = null)
    {
        $lists = $this->getEnabledLanguage();
        /** @var Language $language */
        foreach ($lists as $lang => $language) {
            if (!$language->getInterface()) {
                unset($lists[$lang]);
            }
        }
        if ($language_type) {
            return isset($lists[$language_type]) ? $lists[$language_type] : null;
        }

        return $lists;
    }

    protected function parseSchemaLanguages()
    {
        if (!$this->languages) {
            throw new Exception('No language in system');
        }
        foreach ($this->languages as $key => $language) {
            $lang = new Language();
            $lang->setName($language['name'])->setLanguage($language['language']);
            $lang->setInterface($language['interface'])->setEnable($language['enable']);
            $this->languages[$key] = $lang;
        }
    }

    public function changeUserLanguage($language)
    {
        $languages = $this->getLanguages();
        if (!isset($languages[$language])) {
            return;
        }

        $user = $this->tokenStorkage->getToken()->getUser();
        if ($user instanceof User) {
            $field_data = $user->getData();
            $field_data->setLanguage($language);
            $this->em->persist($field_data);
            $this->em->flush($field_data);
        }
    }
}
