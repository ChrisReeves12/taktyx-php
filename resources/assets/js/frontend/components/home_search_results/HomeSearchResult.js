/**
 * HomeSearchResult
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import React from 'react';
import  HomeSearchResultStatusBar from './HomeSearchResultStatusBar';
import * as HomeSearchResultsActions from 'actions/HomeSearchResultsActions';
import * as SendTaktPopupActions from 'actions/SendTaktPopupActions';
import Util from 'global/Util';

export default class HomeSearchResult extends React.Component
{
    handleUpdateSelected(e)
    {
        HomeSearchResultsActions.updateSearchResultSelect(this.props.idx);
    }

    showTaktPopup(e)
    {
        e.preventDefault();
        let data = {};
        data.recipients = [this.props.data];
        SendTaktPopupActions.showTaktPopup(data);
    }

    render()
    {
        return(
            <div className="home-search-result">
                <div className="img-container">
                    <a href="">
                        <img src={this.props.data.img_url} title={this.props.data.label}
                             alt={this.props.data.label}/>
                    </a>
                </div>
                <div className="info-section">
                    <span className="title">
                        <a href="">{this.props.data.label}</a>
                    </span>
                    <HomeSearchResultStatusBar/>
                    <div className="home-search-result-address-bar">
                        <div className="address_lines">
                            1859 Ponce De Leon Ave, STE 423
                        </div>
                        <div className="city-state-zip">
                            Atlanta, GA 30032
                        </div>
                    </div>
                    <p className="info-desc">
                        {this.props.data.desc}
                    </p>
                    <div className="message-bar">
                        <button onClick={this.showTaktPopup.bind(this)} className="message-button">
                            <img src={Util.staticAssets('img/layout/taktyx_icon1.png')}/> Send Takt
                        </button>
                    </div>
                </div>
                <div onClick={this.handleUpdateSelected.bind(this)} className="checkbox-container">
                    <img src={Util.staticAssets('img/layout/' +
                    (this.props.data.selected ? 'checked' : 'unchecked') + '_box.png')}/>
                </div>
            </div>
        );
    }
}
