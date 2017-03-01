import React, { PropTypes, Component } from 'react';
import {
    Modal as ReactModal,
    ScrollView,
    StyleSheet,
    Text,
    TouchableNativeFeedback,
    View
} from 'react-native';

export default class Modal extends Component {
    render() {
        return (
            <ReactModal
                {...this.props}
                animationType="slide"
                transparent={true}
                supportedOrientations={["portrait"]}
                onOrientationChange={() => {}}
            >
                <View style={styles.modal}>
                    <TouchableNativeFeedback style={styles.background} onPress={this.props.onRequestClose}>
                        <View style={styles.backgroundView}></View>
                    </TouchableNativeFeedback>
                    <View style={styles.container}>
                        {this.props.title ?
                            <Text style={styles.title}>{this.props.title}</Text>
                            : null}
                        <ScrollView style={styles.content}>
                            {this.props.children}
                        </ScrollView>
                    </View>
                </View>
            </ReactModal>
        );
    }
}

Modal.propTypes = {
    title: PropTypes.string,
    visible: PropTypes.bool.isRequired,
    onRequestClose: PropTypes.func.isRequired,
};

const styles = StyleSheet.create({
    modal: {
        position: 'relative',
        flex: 1,
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        justifyContent: 'center',
        alignItems: 'center',
    },
    background: {
        position: 'absolute',
        top: 0,
        bottom: 0,
        left: 0,
        right: 0,
    },
    backgroundView: {
        flex: 1,
        alignSelf: 'stretch',
    },
    container: {
        position: 'absolute',
        justifyContent: 'center',
        alignItems: 'center',
    },
    title: {
        backgroundColor: '#242A31',
        padding: 10,
        color: 'white',
        fontWeight: 'bold',
        fontSize: 15,
        textAlign: 'center',
    },
    content: {
        backgroundColor: 'white',
        padding: 20,
    },
});
