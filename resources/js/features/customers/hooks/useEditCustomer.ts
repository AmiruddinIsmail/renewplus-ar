import { useToast } from "@/hooks/use-toast";
import { Customer } from "@/types/customer";
import { useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

type PropsType = {
    customer: Customer;
};

const useEditCustomer = ({ customer }: PropsType) => {
    const { toast } = useToast();
    const { data, setData, patch, processing, errors } = useForm({
        name: customer.name,
        email: customer.email,
        uuid: customer.uuid,
        phone: customer.phone,
    });

    const onSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        patch(route("customers.update", customer.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: ({ props }) => {
                toast({
                    variant: props.flash?.error ? "destructive" : "default",
                    description:
                        props.flash?.message ||
                        "Customer updated successfully.",
                });
            },
            only: ["customer", "flash"],
        });
    };

    return {
        data,
        setData,
        onSubmit,
        processing,
        errors,
    };
};

export default useEditCustomer;
