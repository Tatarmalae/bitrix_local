<?

namespace Dev;

/**
 * Class Iblock
 * @package Dev
 */
class Iblock {
    /**
     * @var $iblockID
     */
    protected $iblockID;
    /**
     * @var $defaultIblockID
     */
    protected $defaultIblockID = 0;

    /**
     * Iblock constructor.
     * @param $iblockID
     */
    public function __construct($iblockID) {
        if ($iblockID > 0) {
            $this->iblockID = $iblockID;
        } elseif ($this->defaultIblockID > 0) {
            $this->iblockID = $this->defaultIblockID;
        } else {
            return false;
        }
        $this->iblockID = $iblockID;
    }

}