
Hospital WSDL

Namespace: hospital.ac

Services:
	hospInquiryRequest

		-invoice
		-source

	hospInquiryReponse
		-status
		-invoice
		-regno
		-name
		-amount
	
	hopsPostRequest
		-invoice
		-regno
		-name
		-amount
		-transDate
		-transRef
	
	hospPostReponse
		-status
		-message
		-reference

All messages in string Format.
		