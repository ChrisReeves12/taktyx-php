const debug = (process.env.NODE_ENV !== 'production');
const webpack = require('webpack');
const path = require('path');
const fs = require('fs');
const export_val = false;

module.exports = {
    context: path.join(__dirname, "resources", "assets", "js", "frontend"),
    devtool: debug ? "inline-sourcemap" : null,
    cache: false,
    entry: ['babel-polyfill', 'jquery', "./main.js"],
    resolve: {
        alias: {
            stores: path.join(__dirname, "resources", "assets", "js", "frontend", "stores"),
            components: path.join(__dirname, "resources", "assets", "js", "frontend", "components"),
            actions: path.join(__dirname, "resources", "assets", "js", "frontend", "actions"),
            frontend: path.join(__dirname, "resources", "assets", "js", "frontend"),
            page_events: path.join(__dirname, "resources", "assets", "js", "frontend", "page_events"),
            global: path.join(__dirname, "resources", "assets", "js", "global")
        }
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /(node_modules|bower_components)/,
                loader: 'babel-loader',
                query: {
                    presets: ['react', 'es2015', 'stage-0'],
                    plugins: ['react-html-attrs', 'transform-decorators-legacy', 'transform-class-properties', 'transform-object-assign']
                }
            }
        ]
    },

    output: {
        path: path.join(__dirname, 'public', 'js', 'frontend'),
        filename: "combined.min.js"
    },

    plugins: debug ? [] : [
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: JSON.stringify('production')
            }
        }),
        new webpack.optimize.DedupePlugin(),
        new webpack.optimize.OccurenceOrderPlugin(),
        new webpack.optimize.UglifyJsPlugin({ mangle: false, sourcemap: false })
    ]
};