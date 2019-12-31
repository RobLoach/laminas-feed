<?php

/**
 * @see       https://github.com/laminas/laminas-feed for the canonical source repository
 * @copyright https://github.com/laminas/laminas-feed/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-feed/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Feed\Writer\Renderer\Feed;

use DOMDocument;
use DOMElement;
use Laminas\Feed\Writer;
use Laminas\Feed\Writer\Renderer;

/**
* @category Laminas
* @package Laminas_Feed_Writer
*/
class AtomSource extends AbstractAtom implements Renderer\RendererInterface
{

    /**
     * Constructor
     *
     * @param  Writer\Source $container
     */
    public function __construct (Writer\Source $container)
    {
        parent::__construct($container);
    }

    /**
     * Render Atom Feed Metadata (Source element)
     *
     * @return \Laminas\Feed\Writer\Renderer\Feed\Atom
     */
    public function render()
    {
        if (!$this->container->getEncoding()) {
            $this->container->setEncoding('UTF-8');
        }
        $this->dom = new DOMDocument('1.0', $this->container->getEncoding());
        $this->dom->formatOutput = true;
        $root = $this->dom->createElement('source');
        $this->setRootElement($root);
        $this->dom->appendChild($root);
        $this->_setLanguage($this->dom, $root);
        $this->_setBaseUrl($this->dom, $root);
        $this->_setTitle($this->dom, $root);
        $this->_setDescription($this->dom, $root);
        $this->_setDateCreated($this->dom, $root);
        $this->_setDateModified($this->dom, $root);
        $this->_setGenerator($this->dom, $root);
        $this->_setLink($this->dom, $root);
        $this->_setFeedLinks($this->dom, $root);
        $this->_setId($this->dom, $root);
        $this->_setAuthors($this->dom, $root);
        $this->_setCopyright($this->dom, $root);
        $this->_setCategories($this->dom, $root);

        foreach ($this->extensions as $ext) {
            $ext->setType($this->getType());
            $ext->setRootElement($this->getRootElement());
            $ext->setDOMDocument($this->getDOMDocument(), $root);
            $ext->render();
        }
        return $this;
    }

    /**
     * Set feed generator string
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setGenerator(DOMDocument $dom, DOMElement $root)
    {
        if (!$this->getDataContainer()->getGenerator()) {
            return;
        }

        $gdata = $this->getDataContainer()->getGenerator();
        $generator = $dom->createElement('generator');
        $root->appendChild($generator);
        $text = $dom->createTextNode($gdata['name']);
        $generator->appendChild($text);
        if (array_key_exists('uri', $gdata)) {
            $generator->setAttribute('uri', $gdata['uri']);
        }
        if (array_key_exists('version', $gdata)) {
            $generator->setAttribute('version', $gdata['version']);
        }
    }
}
