import React, { Component, PropTypes } from 'react';
import {
    Button,
    StyleSheet,
    Text,
    TextInput,
    View
} from 'react-native';

export default class ConfigurationEdit extends Component {
    constructor(props) {
        super(props);

        this.state = {
            host: this.props.host || '',
            apiKey: this.props.apiKey || '',
        }
    }

    renderTextRow(label, value, onChange) {
        return (
            <View style={styles.rowContainer}>
                <View style={styles.label}>
                    <Text>{label}</Text>
                </View>
                <View>
                    <TextInput
                        style={styles.input}
                        onChangeText={onChange}
                        value={value}
                    />
                </View>
            </View>
        );
    }

    render() {
        return (
            <View style={styles.container}>
                {this.renderTextRow('API url', this.state.host, (value) => this.setState({host: value}))}
                {this.renderTextRow('API key', this.state.apiKey, (value) => this.setState({apiKey: value}))}
                <View style={styles.footerButtons}>
                    <View style={styles.buttonWrapper}>
                        <Button onPress={() => this.props.onTest(this.state.host, this.state.apiKey)} title="Test" color="#0384FF" accessibilityLabel="Test is given configuration is working" />
                    </View>
                    <View style={styles.buttonWrapper}>
                        <Button onPress={() => this.props.onSave(this.state.host, this.state.apiKey)} title="Save" color="#55D062" accessibilityLabel="Save the given configuration" />
                    </View>
                </View>
            </View>
        );
    }
}

ConfigurationEdit.propTypes = {
    host: PropTypes.string,
    apiKey: PropTypes.string,
    onTest: PropTypes.func.isRequired,
    onSave: PropTypes.func.isRequired,
};

const styles = StyleSheet.create({
    rowContainer: {
        flexDirection: 'column',
        marginVertical: 2,
        flex: 1,
    },
    label: {
        width: 115,
        alignItems: 'flex-end',
        marginRight: 10,
        paddingTop: 2,
    },
    buttonWrapper: {
        marginTop: 20,
    },
});
