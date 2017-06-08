/**
 * RecipientLine
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import React from 'react';

export default class RecipientLine extends React.Component
{
    render()
    {
        return(
            <div className="recipient">
                <div>
                    <a href="">
                        <img className="img-fluid" src={this.props.data.img_url}/>
                        <div className="name-section">
                            <h5>{this.props.data.label}</h5>
                            <div className="online-status">
                                <i className="online-status-icon online"/>
                                <div className="status-text online">Online</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        );
    }
}