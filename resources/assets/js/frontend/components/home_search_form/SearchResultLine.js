/**
 * SearchResultLine
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import React from 'react';

export default class SearchResultLine extends React.Component
{
    render()
    {
        window.console.log();
        return(
            <div className="dropdown-search-item">
                <a href={this.props.result.href}>
                    <div href="" className="search-item-photo">
                        <img src={this.props.result.img_url} alt={this.props.result.label}/>
                    </div>
                    <div className="search-item-info">
                        <div className="search-item-info-title">{this.props.result.label}</div>
                        <div className="search-item-info-desc">
                            {this.props.result.desc}
                        </div>
                    </div>
                </a>
            </div>
        );
    }
}
