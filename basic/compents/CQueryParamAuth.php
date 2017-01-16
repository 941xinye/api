<?php
/**
 * Created by PhpStorm.
 * User: gaoyuhong
 * Date: 2016/10/11
 * Time: 上午9:44
 */

namespace app\compents;

class CQueryParamAuth extends \yii\filters\auth\QueryParamAuth
{
    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $headers = $request->headers;
        var_dump($headers);exit;
        $accessToken = $headers->get($this->tokenParam);
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }

}
