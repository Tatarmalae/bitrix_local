<?
namespace Dev;

class Iblock {
    protected $iblockID;
    protected $defaultIblockID = 0;

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