<?

/**
 * После авторизации пользователя устанавливает персональную скидку
 * Class CAeroUserDiscount
 */
class CAeroUserDiscount
{
    /** Метод проверит наличие скидки и установит купон если скидка есть */
    public function OnAfterUserAuthorize()
    {
        global $USER;
        CModule::IncludeModule('catalog');

        $arFilter = array('ID' => $USER->GetID());
        $arParams['SELECT'] = array('UF_DISCOUNT');
        $rsUser = CUser::GetList(($by = 'id'), ($order = 'desc'), $arFilter, $arParams);

        if ($arUser = $rsUser->Fetch()) {

            if (intval($arUser['UF_DISCOUNT']) > 0) {

                /** проверим существование скидки, если нет создадим */
                $discountId = self::getProductDiscounts($arUser['UF_DISCOUNT']);
                if ($discountId > 0) {

                    /** скидка есть проверим купон, купон = ID скидки + ID пользователя + скидка */
                    $userCoupon = $discountId . $arUser['ID'] . $arUser['UF_DISCOUNT'];
                    $userCoupon = preg_replace("/[^0-9]/", '', $userCoupon);

                    $bCoupon = CCatalogDiscountCoupon::IsExistCoupon($userCoupon);
                    if ($bCoupon === false) {
                        $iCoupon = self::createUserCoupon($discountId, $userCoupon);
                    } else {
                        $iCoupon = $userCoupon;
                    }
                    $result = CCatalogDiscountCoupon::SetCoupon($iCoupon);

                } else {

                    /** скидки еще нет создадим ее */
                    $newDiscountId = self::createProductDiscount($arUser['UF_DISCOUNT']);
                    $userCoupon = $newDiscountId . $arUser['ID'] . $arUser['UF_DISCOUNT'];
                    $iCoupon = self::createUserCoupon($newDiscountId, $userCoupon);
                    $result = CCatalogDiscountCoupon::SetCoupon($iCoupon);

                }
            }
        }
    }

    /**
     * Вернет или код скидки или false
     * @param $discount
     * @return bool
     */
    public function getProductDiscounts($discount)
    {
        CModule::IncludeModule('catalog');
        $arFilter = array(
            'ACTIVE' => 'Y',
            'VALUE_TYPE' => 'P',
            'VALUE' => $discount
        );
        $rsProductDiscounts = CCatalogDiscount::GetList(array(), $arFilter, false, false, array('ID'));
        if ($arProductDiscount = $rsProductDiscounts->Fetch()) {

            $arResult = intval($arProductDiscount['ID']);
            return $arResult;

        } else {

            return false;
        }
    }

    /** создадим новую скидку */
    public function createProductDiscount($percent)
    {
        CModule::IncludeModule('catalog');
        $ProductDiscountName = 'Скидка ' . $percent . '% на все товары';
        $arFields = array(
            'SITE_ID' => SITE_ID,
            'ACTIVE' => 'Y',
            'NAME' => $ProductDiscountName,
            'SORT' => 100,
            'VALUE_TYPE' => 'P',
            'VALUE' => $percent,
            'CURRENCY' => 'RUB'
        );
        $arResult = CCatalogDiscount::Add($arFields);
        return $arResult;
    }

    /** создает купон для скидки */
    public function createUserCoupon($discountId, $coupon)
    {
        CModule::IncludeModule('catalog');
        $arFields = array(
            'DISCOUNT_ID' => $discountId,
            'ACTIVE' => 'Y',
            'ONE_TIME' => 'O',
            'COUPON' => $coupon,
            'DESCRIPTION' => 'автоматический купон',
            'DATE_APPLY' => false
        );
        $arResult = CCatalogDiscountCoupon::Add($arFields);
        return $arResult;
    }
    /** удалим купоны перед завершением сеанса */
    public function OnBeforeUserLogout()
    {
        CModule::IncludeModule('catalog');
        CCatalogDiscountCoupon::ClearCoupon();
    }

}
