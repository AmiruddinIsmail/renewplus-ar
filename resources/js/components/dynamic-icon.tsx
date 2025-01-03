import { lazy, Suspense } from "react";
import { LayoutGrid, LucideProps } from "lucide-react";
import dynamicIconImports from "lucide-react/dynamicIconImports";

const fallback = <LayoutGrid />;

export interface IconProps extends Omit<LucideProps, "ref"> {
    name: keyof typeof dynamicIconImports;
}

const DynamicIcon = ({ name, ...props }: IconProps) => {
    const LucideIcon = lazy(dynamicIconImports[name]);

    return (
        <Suspense fallback={fallback}>
            <LucideIcon {...props} />
        </Suspense>
    );
};

export default DynamicIcon;
