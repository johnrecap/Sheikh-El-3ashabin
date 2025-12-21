import { format } from 'date-fns';

export function useMethod() {

    function dynamicId() {
        let digits = '0123456789';
        let dynamicID = '';

        for (let i = 0; i < 11; i++) {
            dynamicID += digits.charAt(Math.floor(Math.random() * digits.length));
        }

        return dynamicID
    }

    function dateTimeNow() {
        const now = new Date();
        const formattedDateTime = format(now, 'dd MMM yyyy, hh:mm:ss aa');
        return formattedDateTime
    }

    function handleDelete(obj, arr) {
        const selectIndex = arr.findIndex(item => item.id == obj.id)
        if(selectIndex !== -1) arr.splice(selectIndex, 1)
    }

    function handleFileUrls(changeEvent, storeVariable) {
        const totalFile = changeEvent.target.files.length

        for(let i=0; i < totalFile; i++) {
            const file = changeEvent.target.files[i]
            
            if(file) {
                const fileSize = (file.size / (1024 * 1024)).toFixed(2) + " mb";

                if(file.type.startsWith('image/')) {
                    const convertURL = URL.createObjectURL(file)
                    storeVariable.push(
                        {
                            id: storeVariable.length + 1,
                            size: fileSize,
                            name: file.name,
                            image: convertURL,
                        }
                    )
                }
            }
        }
    }

    function handleFileUrl(changeEvent, storeVariable) {
        const file = changeEvent.target.files[0] 
        
        if(file) {
            const fileSize = (file.size / (1024 * 1024)).toFixed(2) + " mb";

            if(file.type.startsWith('image/')) {
                const convertURL = URL.createObjectURL(file)
                storeVariable.push(
                    {
                        id: storeVariable.length + 1,
                        name: file.name,
                        size: fileSize,
                        image: convertURL,
                    }
                )
            }
        }
    }

    function handleSlug(param) {
        return param.toLowerCase().replace(/ /g, '-')
    }

    return {
        dynamicId,
        dateTimeNow,
        handleDelete,
        handleFileUrls,
        handleFileUrl,
        handleSlug
    }
}