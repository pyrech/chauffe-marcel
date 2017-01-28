import React, { Component } from 'react';
import {
  AppRegistry,
} from 'react-native';
import App from './src/components/App.js';

export default class ChauffeMarcelApp extends Component {
  render() {
    return (
        <App />
    );
  }
}

AppRegistry.registerComponent('ChauffeMarcelApp', () => ChauffeMarcelApp);
