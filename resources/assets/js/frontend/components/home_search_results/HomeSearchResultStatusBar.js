/**
 * HomeSearchResultStatusBar
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import React from 'react';

export default class HomeSearchResultStatusBar extends React.Component
{
    render()
    {
        return(
            <div className="home-search-result-status-bar">
                <div className="online-status">
                    <i className="online-status-icon online"/> <div className="status-text online">Online</div>
                </div>
                <div className="distance">
                    4.52 miles away.
                </div>
            </div>
        );
    }
}
