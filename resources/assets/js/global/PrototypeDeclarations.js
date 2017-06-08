/**
 * PrototypeDeclaractions
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

Array.prototype.isEmpty = function()
{
    return(this.length === 0);
};

Array.prototype.isNotEmpty = function()
{
    return(this.length > 0);
};