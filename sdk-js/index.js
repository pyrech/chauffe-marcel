export default createApi;
function createApi(options) {
  const basePath = '/api';
  const endpoint = options.endpoint || 'http://127.0.0.1:8000';
  const cors = !!options.cors;
  const mode = cors ? 'cors' : 'basic';
  const securityHandlers = options.securityHandlers || {};
  const handleSecurity = (security, headers, params, operationId) => {
    for (let i = 0, ilen = security.length; i < ilen; i++) {
      let scheme = security[i];
      let schemeParts = Object.keys(scheme);
      for (let j = 0, jlen = schemeParts.length; j < jlen; j++) {
        let schemePart = schemeParts[j];
        let fulfilsSecurityRequirements = securityHandlers[schemePart](
            headers, params, schemePart);
        if (fulfilsSecurityRequirements) {
          return;
        }

      }
    }
    throw new Error('No security scheme was fulfilled by the provided securityHandlers for operation ' + operationId);
  };
  const ensureRequiredSecurityHandlersExist = () => {
    let requiredSecurityHandlers = ['Bearer'];
    for (let i = 0, ilen = requiredSecurityHandlers.length; i < ilen; i++) {
      let requiredSecurityHandler = requiredSecurityHandlers[i];
      if (typeof securityHandlers[requiredSecurityHandler] !== 'function') {
        throw new Error('Expected to see a security handler for scheme "' +
            requiredSecurityHandler + '" in options.securityHandlers');
      }
    }
  };
  ensureRequiredSecurityHandlersExist();
  const buildQuery = (obj) => {
    return Object.keys(obj).map((key) => {
      const value = obj[key];
      if (value === undefined) {
        return '';
      }
      if (value === null) {
        return key;
      }
      if (Array.isArray(value)) {
        if (value.length) {
          return key + '=' + value.map(encodeURIComponent).join('&' + key + '=');
        } else {
          return '';
        }
      } else {
        return key + '=' + encodeURIComponent(value);
      }
    }).join('&');
  };
  return {
    getTimeSlots(params) {
      let headers = {

      };
      handleSecurity([{"Bearer":[]}]
          , headers, params, 'getTimeSlots');
      return fetch(endpoint + basePath + '/time-slots/'
        , {
          method: 'GET',
          headers,
          mode,
        });
    },
    createTimeSlot(params) {
      let headers = {
        'content-type': 'application/json',

      };
      handleSecurity([{"Bearer":[]}]
          , headers, params, 'createTimeSlot');
      return fetch(endpoint + basePath + '/time-slots/'
        , {
          method: 'POST',
          headers,
          mode,
          body: JSON.stringify(params['body']),

        });
    },
    getTimeSlot(params) {
      let headers = {

      };
      handleSecurity([{"Bearer":[]}]
          , headers, params, 'getTimeSlot');
      return fetch(endpoint + basePath + '/time-slots/' + params['uuid'] + ''
        , {
          method: 'GET',
          headers,
          mode,
        });
    },
    updateTimeSlot(params) {
      let headers = {
        'content-type': 'application/json',

      };
      handleSecurity([{"Bearer":[]}]
          , headers, params, 'updateTimeSlot');
      return fetch(endpoint + basePath + '/time-slots/' + params['uuid'] + ''
        , {
          method: 'PUT',
          headers,
          mode,
          body: JSON.stringify(params['body']),

        });
    },
    deleteTimeSlot(params) {
      let headers = {

      };
      handleSecurity([{"Bearer":[]}]
          , headers, params, 'deleteTimeSlot');
      return fetch(endpoint + basePath + '/time-slots/' + params['uuid'] + ''
        , {
          method: 'DELETE',
          headers,
          mode,
        });
    },
    getMode(params) {
      let headers = {

      };
      handleSecurity([{"Bearer":[]}]
          , headers, params, 'getMode');
      return fetch(endpoint + basePath + '/mode/'
        , {
          method: 'GET',
          headers,
          mode,
        });
    },
    updateMode(params) {
      let headers = {
        'content-type': 'application/json',

      };
      handleSecurity([{"Bearer":[]}]
          , headers, params, 'updateMode');
      return fetch(endpoint + basePath + '/mode/'
        , {
          method: 'POST',
          headers,
          mode,
          body: JSON.stringify(params['body']),

        });
    },

  };
}
