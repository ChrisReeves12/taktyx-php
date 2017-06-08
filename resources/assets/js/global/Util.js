/**
 * Util
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

export default class Util
{
    /**
     * Get geological location
     */
    static getGeoLocation()
    {
        if(navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition((pos) => {
                console.log(pos);
            });
        }
    }

    static staticAssets(file)
    {
        return window.taktyx.assets_path + file;
    }
}
