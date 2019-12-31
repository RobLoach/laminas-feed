<?php

/**
 * @see       https://github.com/laminas/laminas-feed for the canonical source repository
 * @copyright https://github.com/laminas/laminas-feed/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-feed/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Feed\Reader\Feed\Atom;

use DOMElement;
use DOMXPath;
use Laminas\Feed\Reader;
use Laminas\Feed\Reader\Feed;

/**
* @category Laminas
* @package Reader
*/
class Source extends Feed\Atom
{

    /**
     * Constructor: Create a Source object which is largely just a normal
     * Laminas\Feed\Reader\AbstractFeed object only designed to retrieve feed level
     * metadata from an Atom entry's source element.
     *
     * @param DOMElement $source
     * @param string $xpathPrefix Passed from parent Entry object
     * @param string $type Nearly always Atom 1.0
     */
    public function __construct(DOMElement $source, $xpathPrefix, $type = Reader\Reader::TYPE_ATOM_10)
    {
        $this->domDocument = $source->ownerDocument;
        $this->xpath = new DOMXPath($this->domDocument);
        $this->data['type'] = $type;
        $this->registerNamespaces();
        $this->loadExtensions();

        $manager = Reader\Reader::getExtensionManager();
        $extensions = array('Atom\Feed', 'DublinCore\Feed');

        foreach ($extensions as $name) {
            $extension = $manager->get($name);
            $extension->setDomDocument($this->domDocument);
            $extension->setType($this->data['type']);
            $extension->setXpath($this->xpath);
            $this->extensions[$name] = $extension;
        }

        foreach ($this->extensions as $extension) {
            $extension->setXpathPrefix(rtrim($xpathPrefix, '/') . '/atom:source');
        }
    }

    /**
     * Since this is not an Entry carrier but a vehicle for Feed metadata, any
     * applicable Entry methods are stubbed out and do nothing.
     */

    /**
     * @return void
     */
    public function count() {}

    /**
     * @return void
     */
    public function current() {}

    /**
     * @return void
     */
    public function key() {}

    /**
     * @return void
     */
    public function next() {}

    /**
     * @return void
     */
    public function rewind() {}

    /**
     * @return void
     */
    public function valid() {}

    /**
     * @return void
     */
    protected function indexEntries() {}
}
